<?php

namespace Modules\App\Services\PaymentGateway\Daraja;

class DarajaService{
    
    public function stk()
    {
        return new STKService();
    }

    public function b2c()
    {
        return new B2CService();
    }

    public function c2b()
    {
        return new C2BService();
    }

    public function reconciliation()
    {
        return new ReconciliationService();
    }
}