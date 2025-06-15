<?php

namespace Modules\App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Koverae\KoveraeBilling\Models\PlanSubscription;
use Koverae\KoveraeBilling\Models\Transaction;
use Modules\App\Services\PaymentGateway\PaystackService;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaystackController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function initiate(Request $request)
    {
        $payload = [
            'name' => $request->name,
            'email' => $request->email ?? 'laudbouet@gmail.com',
            'amount' => $request->amount * 100, // Convert to kobo
        ];
        $this->paystackService->initializePayment(
            $payload,
            [
                'source' => $request->source ?? 'pos',
                'company_id' => Auth::user()->company_id ?? null,
            ]
        );
    }

    public function callback(Request $request)
    {
        $paystackService = new PaystackService();
        return $paystackService->handleCallback($request);
    }

    public function handle(Request $request)
    {
        $paystackService = new PaystackService();
        return $paystackService->handleWebhook($request);
    }


}
