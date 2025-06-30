<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

use Illuminate\Support\Facades\Http;

class BaseDarajaService{
    protected string $baseUrl, $consumerKey, $consumerSecret, $initiatorPassword;

    public function __construct()
    {
        $this->baseUrl = config('daraja.base_url');
    }

    protected function generateAccessToken()
    {
        $response = Http::withBasicAuth(
            config('daraja.consumer_key'),
            config('daraja.consumer_secret')
        )->get("{$this->baseUrl}/oauth/v1/generate?grant_type=client_credentials");

        return $response->json('access_token');
    }

    protected function generateSecurityCredential()
    {
        $certPath = storage_path('app/keys/sandbox_cert.cer'); // Update path if needed.
        $publicKey = file_get_contents($certPath);

        openssl_public_encrypt(
            config('daraja.initiator_password'),
            $encrypted,
            $publicKey,
            OPENSSL_PKCS1_PADDING
        );

        return base64_encode($encrypted);
    }

}