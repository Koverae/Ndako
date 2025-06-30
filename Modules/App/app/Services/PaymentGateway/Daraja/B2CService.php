<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

use Illuminate\Support\Facades\Http;


class B2CService extends BaseDarajaService
{
    public function sendPayment($phone, $amount, $remarks = 'Ndako Payout')
    {
        $payload = [
            'InitiatorName' => config('daraja.b2c_initiator'),
            'SecurityCredential' => $this->generateSecurityCredential(),
            'CommandID' => 'BusinessPayment', // or 'SalaryPayment' or 'PromotionPayment'
            'Amount' => $amount,
            'PartyA' => config('daraja.shortcode'),
            'PartyB' => $phone,
            'Remarks' => $remarks,
            'QueueTimeOutURL' => config('daraja.b2c_timeout_url'),
            'ResultURL' => config('daraja.b2c_result_url'),
            'Occasion' => 'Ndako'
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/b2c/v3/paymentrequest", $payload)
            ->json();
    }

    public function reverseTransaction($transactionID, $amount, $receiverParty, $remarks = 'Ndako Reversal')
    {
        $payload = [
            "Initiator" => config('daraja.b2c_initiator'),
            "SecurityCredential" => $this->generateSecurityCredential(),
            "CommandID" => "TransactionReversal",
            "TransactionID" => $transactionID,
            "Amount" => $amount,
            "ReceiverParty" => $receiverParty,
            "RecieverIdentifierType" => "11",
            "Remarks" => $remarks,
            "QueueTimeOutURL" => config('daraja.b2c_timeout_url'),
            "ResultURL" => config('daraja.b2c_result_url'),
        ];

        $response = Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/reversal/v1/request", $payload);

        return $response->json();
    }

}