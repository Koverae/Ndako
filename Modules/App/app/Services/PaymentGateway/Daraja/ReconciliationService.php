<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

use Illuminate\Support\Facades\Http;

class ReconciliationService extends BaseDarajaService
{
    public function accountBalance()
    {
        $payload = [
            'Initiator' => config('daraja.b2c_initiator'),
            'SecurityCredential' => $this->generateSecurityCredential(),
            'CommandID' => 'AccountBalance',
            'PartyA' => config('daraja.shortcode'),
            'IdentifierType' => 4, // 4 = Shortcode
            'Remarks' => 'Balance check',
            'QueueTimeOutURL' => config('daraja.b2c_timeout_url'),
            'ResultURL' => config('daraja.b2c_result_url'),
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/accountbalance/v1/query", $payload)
            ->json();
    }

    public function transactionStatus($transactionID)
    {
        $payload = [
            'Initiator' => config('daraja.b2c_initiator'),
            'SecurityCredential' => $this->generateSecurityCredential(),
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => config('daraja.shortcode'),
            'IdentifierType' => 1, // 1 = MSISDN
            'Remarks' => 'Transaction status check',
            'QueueTimeOutURL' => config('daraja.b2c_timeout_url'),
            'ResultURL' => config('daraja.b2c_result_url'),
            'TransactionID' => $transactionID,
            'Occasion' => 'Ndako',
        ];

        return Http::withToken($this->generateAccessToken())
            ->post("{$this->baseUrl}/mpesa/transactionstatus/v1/query", $payload)
            ->json();
    }
    
}