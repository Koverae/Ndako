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

        if (!$response->ok()) {
            logger()->error('Pesapal Auth failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return '';
        }

        return $response->json('token');
    }

    public function makeOrder(array $data): array
    {
        $payload = [
            "id" => $data['id'] ?? uniqid('order_'),
            "currency" => "KES",
            "amount" => $data['amount'],
            "description" => $data['description'] ?? 'Payment',
            "callback_url" => config('pesapal.callback_url'),
            "notification_id" => config('pesapal.ipn_id'),
            "billing_address" => [
                "email_address" => $data['email'],
                "phone_number" => $data['phone'],
                "country_code" => "KE",
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name']
            ]
        ];


        $res = Http::withToken($this->token)
            ->post(config('pesapal.base_url') . '/api/Transactions/SubmitOrderRequest', $payload);

        return $res->json();
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
