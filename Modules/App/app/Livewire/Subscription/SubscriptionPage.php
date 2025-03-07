<?php

namespace Modules\App\Livewire\Subscription;

use Illuminate\Support\Facades\Auth;
use Koverae\KoveraeBilling\Models\Plan;
use Livewire\Component;
use Modules\App\Services\PaymentGateway\PaystackService;

class SubscriptionPage extends Component
{
    public $plans, $billingCycle, $selectedPlan, $amount = 10, $email;

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
        $this->billingCycle = 'month';
        $this->plans = Plan::where('invoice_interval', $this->billingCycle)
          ->where('price', '>', 1)
            ->get();
        $this->email = Auth::user()->email;
    }

    public function updatedBillingCycle(){
        $this->plans = Plan::where('invoice_interval', $this->billingCycle)
          ->where('price', '>', 1)
            ->get();
        $this->selectedPlan = '';
    }

    public function updatedSelectedPlan(){
        $plan = Plan::getByTag($this->selectedPlan);
        $this->amount = $plan->price;
    }

    public function render()
    {
        return view('app::livewire.subscription.subscription-page')
        ->extends('layouts.auth')->section('page_content');
    }

    public function initiatePayment()
    {
        // $this->validate();
        $this->paystackService->initializePayment(
             $this->email,
            $this->amount,
        );


    }
}
