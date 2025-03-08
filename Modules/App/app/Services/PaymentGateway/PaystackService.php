<?php

namespace Modules\App\Services\PaymentGateway;

use App\Models\Team\Team;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Koverae\KoveraeBilling\Models\Transaction;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackService
{    public function initializePayment($email, $amount)
    {
        $amount = $amount * 100; // Paystack uses kobo (cents), so multiply by 100
        $client = new Client();
        $response = $client->post(env('PAYSTACK_PAYMENT_URL') . '/transaction/initialize', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'email' => $email,
                'amount' => $amount,
                'callback_url' => route('paystack.callback')
            ]
        ]);

        $result = json_decode($response->getBody());

        if ($result->status) {
            return redirect($result->data->authorization_url);
        }

        return back()->with('error', 'Payment initiation failed.');

    }


    // Callback after payment
    public function handleCallback(Request $request)
    {
        $reference = $request->query('reference');
        $client = new Client();
        $baseUrl = env('PAYSTACK_PAYMENT_URL', 'https://api.paystack.co');

        $response = $client->get($baseUrl . '/transaction/verify/' . $reference, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('PAYSTACK_SECRET_KEY'),
            ]
        ]);

        $result = json_decode($response->getBody());

        $team = Team::find(current_company()->team->id);
        $subscription = $team->subscription('main');

        if (!$result->status || $result->data->status !== 'success') {
            Transaction::create([
                'team_id' => $team->id,
                'subscription_id' => $subscription->id,
                'reference' => $result->data->reference,
                'amount' => $result->data->amount / 100,
                'status' => 'failed',
                'payment_method' => $result->data->channel,
                'metadata' => json_encode($result->data),
            ]);

            return view('app::paystack.error', ['message' => 'Payment failed. Please try again.']);
        }

        DB::transaction(function () use ($subscription, $result, $team) {
            $subscription->update([
                'starts_at' => now(),
                'ends_at' => calculateEndDate($subscription->invoice_interval ?? 'monthly'),
            ]);

            Transaction::create([
                'team_id' => $team->id,
                'subscription_id' => $subscription->id,
                'reference' => $result->data->reference,
                'amount' => $result->data->amount / 100,
                'status' => 'success',
                'payment_method' => $result->data->channel,
                'metadata' => json_encode($result->data),
            ]);
        });

        return view('app::paystack.success', ['data' => $result->data]);
    }

}
