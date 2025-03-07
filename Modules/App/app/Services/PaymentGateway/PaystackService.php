<?php

namespace Modules\App\Services\PaymentGateway;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
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

        if ($result->status) {
            // Handle successful payment (e.g., update database, send email)
            return view('paystack.success', ['data' => $result->data]);
        }

        return view('paystack.error', ['message' => $result->message]);
    }

    public function verifyPayment($reference)
    {
        $paymentDetails = Paystack::getPaymentData();

        if ($paymentDetails['status'] && $paymentDetails['data']['status'] === 'success') {
            return $paymentDetails['data'];
        }

        return null;
    }
}
