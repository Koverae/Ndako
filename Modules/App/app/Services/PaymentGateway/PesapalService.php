<?php

namespace Modules\App\Services\PaymentGateway;

use Illuminate\Support\Facades\Http;

class PesapalService{

    protected string $token;

    public function __construct()
    {
        $this->token = $this->authenticate();
    }

    protected function authenticate(): string
    {
        $response = Http::withBasicAuth(
            config('pesapal.consumer_key'),
            config('pesapal.consumer_secret')
        )->post(config('pesapal.base_url') . '/api/Auth/RequestToken');

        return $response->json('token');
    }

    public function makeOrder(array $data): array
    {
        $response = Http::withToken($this->token)->post(
            config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest',
            [
                "id" => $data['id'] ?? 'order-id-' . time(),
                "currency" => "KES",
                "amount" => $data['amount'],
                "description" => $data['description'] ?? 'Payment for order',
                "callback_url" => config('pesapal.callback_url'),
                "notification_id" => "your-ipn-url-uuid", // You must register this on Pesapal
                "billing_address" => [
                    "email_address" => "jane@example.com",
                    "phone_number" => "0700000000",
                    "country_code" => "KE",
                    "first_name" => "Jane",
                    "last_name" => "Mwangi"
                ]
            ]
        );

        return $response->json();
    }

    public function getTransactionStatus(string $trackingId): array
    {
        $response = Http::withToken($this->token)->get(
            config('pesapal.base_url') . "/api/Transactions/GetTransactionStatus",
            ['orderTrackingId' => $trackingId]
        );

        return $response->json();
    }
}
