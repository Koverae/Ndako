<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

use Illuminate\Support\Facades\Http;

class C2BService extends BaseDarajaService
{
    public function registerUrls()
    {
        $payload = [
            'ShortCode' => config('daraja.shortcode'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => config('daraja.confirmation_url'),
            'ValidationURL' => config('daraja.validation_url'),
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/c2b/v1/registerurl", $payload)
            ->json();
    }
}