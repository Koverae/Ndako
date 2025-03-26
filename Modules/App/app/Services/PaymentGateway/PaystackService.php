<?php

namespace Modules\App\Services\PaymentGateway;

use App\Models\Team\Team;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Koverae\KoveraeBilling\Models\PlanSubscription;
use Koverae\KoveraeBilling\Models\Transaction;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\RevenueManager\Models\Accounting\Journal;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->secretKey = settings()->paystack_secret_key; // Store in .env
        $this->baseUrl = settings()->paystack_base_url;
    }

    /**
     * Handle payment processing and recording.
     *
     * @param string $method Payment method (cash, bank, mobile_money, paystack)
     * @param float $amount Payment amount
     * @param int $bookingId Associated booking ID
     * @param array $extraData Additional data (e.g., transaction reference)
     * @return Payment|null
     */
    public function handlePayment(string $method, float $amount, int $bookingId, array $extraData = []): ?Payment
    {
        $transactionId = Str::uuid(); // Generate unique transaction ID

        // If Paystack, process the payment
        if ($method === 'paystack') {
            $paystackResponse = $this->initializePayment($amount, $extraData);

            if (!$paystackResponse || !isset($paystackResponse['data']['id'])) {
                Log::error('Paystack payment failed', ['response' => $paystackResponse]);
                return null;
            }

            $transactionId = $paystackResponse['data']['id'];
        }

        // Store payment record
        return BookingPayment::create([
            'booking_id' => $bookingId,
            'method' => $method,
            'amount' => $amount,
            'transaction_id' => $transactionId,
            'status' => 'completed',
        ]);
    }


    /**
    * Initialize a payment with Paystack.
    *
    * @param string $email The customer's email address.
    * @param float $amount The payment amount (in major currency units, e.g., KES).
    * @return \Illuminate\Http\RedirectResponse Redirects to Paystack's payment page.
    */
    public function initializePayment($name = null, $email, $amount, $extraData)
    {
        $amount = $amount * 100; // Paystack processes payments in kobo (cents), so multiply by 100.
        $client = new Client();

        $response = $client->post($this->baseUrl  . '/transaction/initialize', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $name,
                'email' => $email,
                'amount' => $amount,
                'callback_url' => route('paystack.callback'),// Redirect after payment
                'metadata' => $extraData
            ]
        ]);

        $result = json_decode($response->getBody());

        // Redirect to Paystack payment page if the initialization was successful
        if ($result->status) {
            // return redirect($result->data->authorization_url);
            return $result;
        }

        // Return with an error message if initialization failed
        return back()->with('error', 'Payment initiation failed.');

    }

    /**
     * Handle Paystack's callback after payment.
     *
     * @param \Illuminate\Http\Request $request The request instance containing the payment reference.
     */
    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference'); // Get the payment reference from URL
        $client = new Client();

        // Verify the transaction with Paystack
        $response = $client->get($this->baseUrl . '/transaction/verify/' . $reference, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->secretKey,
            ]
        ]);

        $result = json_decode($response->getBody());

        // If payment failed, store the transaction as "failed" and return an error page
        if (!$result->status || $result->data->status !== 'success') {
            session()->flash('error', "Oops! Something went wrong. Please try again later. Your reference: {$reference}");
            return view('app::paystack.callback', ['data' => $result->data]);
        }

        $invoice = BookingInvoice::find($result->data->metadata->invoiceId);
        // Process the payment and update records within a database transaction
        DB::transaction(function () use ($invoice, $result) {
            
            // Store payment record
            $journal = Journal::isCompany(current_company()->id)->isType($result->data->metadata->method)->first();
            $payment = BookingPayment::create([
                'company_id' => current_company()->id,
                'booking_invoice_id' => $invoice->id,
                'transaction_id' => $result->data->reference,
                'journal_id' => $journal->id,
                'payment_method' => $result->data->channel,
                // 'payment_method' => $result->data->metadata->method,
                'amount' => $result->data->amount / 100,
                'date' => now(),
                'note' => 'Payment Received for Invoice #'. $invoice->reference,
                'type' => 'credit',
            ]);
            $payment->save();

            // Process the after payment

            $due_amount = $invoice->due_amount - $payment->amount;

            if ($due_amount == $invoice->total_amount) {
                $payment_status = 'unpaid';
            } elseif ($due_amount > 0) {
                $payment_status = 'partial';
            } else {
                $payment_status = 'paid';
            }
            $paidAmount = $invoice->paid_amount + $payment->amount;

            $invoice->update([
                'payment_status' => $payment_status,
                'paid_amount' => ($paidAmount),
                'due_amount' => ($due_amount),
            ]);

            $invoice->booking->update([
                'payment_status' => $payment_status,
                'paid_amount' => ($paidAmount),
                'due_amount' => ($due_amount),
            ]);
            // Update Payment due amount
            $payment->update([
                'due_amount' => ($due_amount)
            ]);

        });

        // Return the success view with payment details
        session()->flash('success', "Your payment was received successfully! Your reference is: {$reference}");
        return view('app::paystack.callback', ['data' => $result->data]);
    }

    /**
     * Handle Paystack Webhook Events.
     *
     * @param \Illuminate\Http\Request $request The request containing the webhook payload.
     * @return \Illuminate\Http\JsonResponse Responds with a success or error message.
     */
    public function handle(Request $request)
    {
        // Verify webhook signature to ensure request authenticity
        $signature = $request->header('X-Paystack-Signature');

        if (!$signature || $signature !== hash_hmac('sha512', $request->getContent(), $this->secretKey)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Decode and log the webhook payload
        $payload = $request->all();
        Log::info('Paystack Webhook Event:', $payload);

        // Handle different webhook event types
        if ($payload['event'] === 'charge.success') {
            $reference = $payload['data']['reference']; // Transaction reference
            $status = $payload['data']['status']; // Payment status
            $amount = $payload['data']['amount'] / 100; // Convert from kobo to major currency
            $teamId = $payload['data']['metadata']['team_id'] ?? null; // Retrieve team ID from metadata

            // Find and update the corresponding transaction record
            $transaction = Transaction::where('reference', $reference)->first();
            if ($transaction) {
                $transaction->update([
                    'status' => $status,
                    'processed_at' => now(),
                ]);
            }

            // If a valid team ID is present, update the team's subscription
            if ($teamId) {
                $subscription = PlanSubscription::find('subscriber_id', $teamId) ?? null;
                if ($subscription) {
                    $subscription->update([
                        'starts_at' => now(),
                        'ends_at' => calculateEndDate($subscription->invoice_interval),
                    ]);
                }
            }

            return response()->json(['message' => 'Payment processed successfully']);
        }

        // Return an error response if the event is not handled
        return response()->json(['message' => 'Event not handled'], 400);
    }


}
