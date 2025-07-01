<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

use Illuminate\Support\Facades\Http;

class STKService extends BaseDarajaService
{
    public function initiateStkPush($phone, $amount, $accountRef, $desc)
    {
        $businessCode = config('daraja.shortcode');
        $shortCode = config('daraja.shortcode');
        $timestamp = now()->format('YmdHis');
        $password = base64_encode(
            config('daraja.shortcode') . config('daraja.passkey') . $timestamp
        );

        $payload = [
            "BusinessShortCode" => $businessCode,
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $phone,
            "PartyB" => $shortCode,
            "PhoneNumber" => $phone,
            "CallBackURL" => url('/api/v1/payments/mpesa/stk-callback'),
            "AccountReference" => $accountRef,
            "TransactionDesc" => $desc,
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/stkpush/v1/processrequest", $payload)
            ->json();
    }

    public function queryStkPushStatus($checkoutRequestID)
    {
        $timestamp = now()->format('YmdHis');
        $password = base64_encode(
            config('daraja.shortcode') . config('daraja.passkey') . $timestamp
        );

        $payload = [
            "BusinessShortCode" => config('daraja.shortcode'),
            "Password" => $password,
            "Timestamp" => $timestamp,
            "CheckoutRequestID" => $checkoutRequestID,
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/stkpushquery/v1/query", $payload)
            ->json();
    }
}
