<?php

namespace Modules\App\Livewire\Subscription;

use Illuminate\Support\Facades\Auth;
use Koverae\KoveraeBilling\Models\Plan;
use Koverae\KoveraeBilling\Services\PaymentMethods\Paystack;
use Livewire\Component;
use Modules\App\Services\PaymentGateway\PaystackService;

class SubscriptionPage extends Component
{
    public $renew;
    public $plans, $billingCycle, $invoicePeriod = 1, $selectedPlan, $amount = 0, $roomCount = 1, $email, $plan;
    protected $queryString = ['renew'];

    protected $rules = [
        'email' => 'required|email',
        'amount' => 'required|numeric|min:1',
    ];

    protected $paystackService;

    public function boot(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    public function mount(){

        $this->selectedPlan = current_company()->team->subscription('main')->plan->tag;
        $this->billingCycle = 'month';
        $this->roomCount = current_company()->size;
        $this->plans = Plan::where('is_active', true)->where('invoice_interval', $this->billingCycle)
          ->where('price', '>', 1)
            ->get();
        $this->email = Auth::user()->email;
        $this->updatedSelectedPlan();
    }

    public function updatedBillingCycle(){
        $this->plans = Plan::where('is_active', true)->where('invoice_interval', $this->billingCycle)
          ->where('price', '>', 1)
            ->get();
        $this->selectedPlan = '';
    }

    public function updatedSelectedPlan(){
        $plan = Plan::getByTag($this->selectedPlan);
        $this->amount = ($plan->discounted_price * max(1, $this->roomCount) * $this->invoicePeriod);
        $this->plan = $plan;
    }

    public function updatedRoomCount(){
        $this->updatedSelectedPlan();
    }

    public function render()
    {
        return view('app::livewire.subscription.subscription-page')
        ->extends('layouts.auth')->section('page_content');
    }

    public function initiatePayment(Paystack $paystack)
    {
        // $this->validate();
        $paystack->initializePayment(
            current_company()->name,
            $this->email,
            $this->amount,
            $this->plan->plan_code,
            $this->invoicePeriod,
            $this->billingCycle
        );
    }


    public function increaseInvoicePeriod(){
        if($this->selectedPlan){
            $this->invoicePeriod++;
            $this->updatedSelectedPlan();
        }
    }

    public function decreaseInvoicePeriod(){
        if($this->invoicePeriod >= 1 && $this->selectedPlan){
            $this->invoicePeriod--;
            $this->updatedSelectedPlan();
        }
    }
}
