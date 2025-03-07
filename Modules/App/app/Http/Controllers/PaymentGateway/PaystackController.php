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
                $request->amount,
                route('paystack.callback')
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

        // if (!$paymentDetails) {
        //     return redirect()->route('subscribe')->with('error', 'Payment verification failed.');
        // }

        // Update user subscription
        // current_company()->team->
        // Auth::user()->update(['subscription_status' => 'active']);
    }

    public function redirectToGateway(Request  $request)
    {
        try{
            $request->request->add([
                "email"    => "laudbouetoumoussa@gmail.com",
                "orderID"  => "123456", // anything
                "amount"   => 100,
                "quantity" => 1,
                "currency" => "KES", // change as per need
                "reference"=> Paystack::genTranxRef(),
                "metadata" => json_encode(['key_name' => 'value']), // this should be related data
            ]);

            return Paystack::getAuthorizationUrl()->redirectNow();
        }catch(\Exception $e) {
            return Redirect::back()->withMessage(['msg'=>'The paystack token has expired. Please refresh the page and try again.', 'type'=>'error']);
        }
    }

   public function handleGatewayCallback(Request  $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        dd($paymentDetails);
    }

}
