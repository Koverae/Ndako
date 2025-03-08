<?php

namespace Modules\App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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
        // $request->validate([
        //     'email' => 'required|email',
        //     'amount' => 'required|numeric|min:1',
        // ]);

        try {
            $paymentUrl = $this->paystackService->initializePayment(
                $request->email,
                $request->amount
            );

            return redirect($paymentUrl);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $paystackService = new PaystackService();
        return $paystackService->handleCallback($request);
    }


}
