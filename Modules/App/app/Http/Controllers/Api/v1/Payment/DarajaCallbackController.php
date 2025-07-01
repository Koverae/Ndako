<?php

namespace Modules\App\Http\Controllers\Api\V1\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Pos\Models\Order\PosOrderPayment;
use Modules\RevenueManager\Models\Accounting\Journal;

class DarajaCallbackController extends Controller
{
    public function stkCallback(Request $request)
    {
        Log::info('STK Callback:', $request->all());

        $data = $request->all();

        $resultCode = data_get($data, 'Body.stkCallback.ResultCode');

        $metadata = collect(data_get($data, 'Body.stkCallback.CallbackMetadata.Item', []));
        $amount = $metadata->where('Name', 'Amount')->first()['Value'] ?? null;
        $mpesaReceipt = $metadata->where('Name', 'MpesaReceiptNumber')->first()['Value'] ?? null;
        $invoiceId = $metadata->where('Name', 'AccountReference')->first()['Value'] ?? null;

        if ($resultCode === 0 && $invoiceId) {

            // Try to find a Booking Invoice first
            $invoice = BookingInvoice::findByReference($invoiceId);
            if ($invoice) {

                DB::transaction(function () use ($invoice, $mpesaReceipt, $amount) {
                    $journal = Journal::isCompany(current_company()->id)
                        ->isType('m-pesa')
                        ->first();

                    $payment = BookingPayment::create([
                        'company_id'         => current_company()->id,
                        'booking_invoice_id' => $invoice->id,
                        'transaction_id'     => $mpesaReceipt,
                        'journal_id'         => $journal->id,
                        'payment_method'     => 'm-pesa',
                        'amount'             => $amount,
                        'date'               => now(),
                        'note'               => 'Payment Received for Invoice #' . $invoice->reference,
                        'type'               => 'credit',
                    ]);

                    $due = $invoice->due_amount - $payment->amount;
                    $status = $due === $invoice->total_amount ? 'unpaid' : ($due > 0 ? 'partial' : 'paid');

                    $invoice->update([
                        'payment_status' => $status,
                        'paid_amount'    => $invoice->paid_amount + $payment->amount,
                        'due_amount'     => $due,
                    ]);

                    $invoice->booking->update([
                        'payment_status' => $status,
                        'paid_amount'    => $invoice->paid_amount + $payment->amount,
                        'due_amount'     => $due,
                    ]);

                    $payment->update(['due_amount' => $due]);
                });

            } else {
                // If not found, try to find a PosOrder
                $order = PosOrder::findByToken($invoiceId);
                if($order){
                    DB::transaction(function () use ($order, $mpesaReceipt, $amount) {

                        $payment = PosOrderPayment::create([
                            'company_id'     => current_company()->id,
                            'pos_id'         => $order->pos_id,
                            'pos_order_id'   => $order->id,
                            'pos_session_id' => $order->pos_session_id ?? null,
                            'guest_id'       => $order->guest_id ?? null,
                            'payment_method' => 'mpesa',
                            'amount'         => $amount ?? 0,
                            'date'           => now(),
                            'transaction_id' => $mpesaReceipt ?? '',
                            'label'          => 'Payment Received for Order #' . $order->receipt_number,
                        ]);

                        $dueAmount = $order->due_amount - $payment->amount;

                        $paymentStatus = match (true) {
                            $dueAmount == $order->total_amount => 'unpaid',
                            $dueAmount > 0 => 'partial',
                            default => 'paid',
                        };

                        $paidAmount = $order->paid_amount + $payment->amount;

                        $order->update([
                            'status' => 'receipt',
                            'payment_method' => 'mpesa',
                            'payment_status' => $paymentStatus,
                            'paid_amount'    => $paidAmount,
                            'due_amount'     => $dueAmount,
                        ]);

                    });
                }

            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'STK Callback processed']);
    }

    public function b2cResult(Request $request)
    {
        Log::info('B2C Result:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function b2cTimeout(Request $request)
    {
        Log::warning('B2C Timeout:', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function c2bConfirmation(Request $request)
    {
        Log::info('C2B Confirmation:', $request->all());

        // Save payment data (e.g. payment log, link to tenant invoice, etc)
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function c2bValidation(Request $request)
    {
        Log::info('C2B Validation:', $request->all());

        // Optionally validate details before accepting payment
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function reversal(Request $request)
    {
        Log::info('Reversal Callback:', $request->all());

        // Handle reversal result: update payment status, log, etc.
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Reversal Received']);
    }

}
