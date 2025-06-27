<?php

namespace Modules\App\Livewire\Subscription;

use Illuminate\Support\Facades\Auth;
use Koverae\KoveraeBilling\Models\Plan;
use Koverae\KoveraeBilling\Services\PaymentMethods\Paystack;
use Livewire\Component;
use Modules\App\Services\PaymentGateway\PaystackService;

class SubscriptionPage extends Component
{
    public ?bool $renew = null;
    public $plans = [];
    public string $billingCycle = 'month';
    public int $invoicePeriod = 1;
    public ?string $selectedPlan = null;
    public float $amount = 0;
    public int $roomCount = 1;
    public string $email = '';
    public ?Plan $plan = null;

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

    public function mount(): void
    {
        $company = current_company();
        $this->selectedPlan = optional(optional($company->team)->subscription('main'))->plan->tag ?? null;
        $this->billingCycle = 'month';
        $this->roomCount = max(1, optional($company->units)->count() ?? 1);

        $this->plans = Plan::where('is_active', true)
            ->where('invoice_interval', $this->billingCycle)
            ->where('price', '>', 1)
            ->get();
        $this->email = Auth::user()->email ?? '';
        $this->updatedSelectedPlan();
    }

    public function updatedBillingCycle(): void
    {
        $this->plans = Plan::where('is_active', true)
            ->where('invoice_interval', $this->billingCycle)
            ->where('price', '>', 1)
            ->get();
        $this->selectedPlan = null;
        $this->plan = null;
        $this->amount = 0;
    }

    public function updatedSelectedPlan(): void
    {
        if (!$this->selectedPlan) {
            $this->plan = null;
            $this->amount = 0;
            return;
        }

        $plan = Plan::getByTag($this->selectedPlan);
        if (!$plan) {
            $this->plan = null;
            $this->amount = 0;
            return;
        }

        $this->plan = $plan;
        $this->amount = ($plan->discounted_price * max(1, $this->roomCount) * max(1, $this->invoicePeriod));
    }

    public function updatedroomCount(): void
    {
        $company = current_company();
        $minRooms = max(1, optional($company->units)->count() ?? 1);
        if ($this->roomCount < $minRooms) {
            $this->roomCount = $minRooms;
        }
        $this->updatedSelectedPlan();
    }

    public function render()
    {
        return view('app::livewire.subscription.subscription-page')
            ->extends('layouts.auth')
                ->section('page_content');
    }

    public function initiatePayment(Paystack $paystack): void
    {
        $this->validate();

        if (!$this->plan) {
            $this->addError('plan', 'Please select a valid plan.');
            return;
        }

        $paystack->initializePayment(
            current_company()->name,
            $this->email,
            $this->amount,
            $this->plan->plan_code,
            $this->invoicePeriod,
            $this->billingCycle
        );
    }

    public function increaseInvoicePeriod(): void
    {
        if ($this->selectedPlan) {
            $this->invoicePeriod++;
            $this->updatedSelectedPlan();
        }
    }

    public function decreaseInvoicePeriod(): void
    {
        if ($this->invoicePeriod > 1 && $this->selectedPlan) {
            $this->invoicePeriod--;
            $this->updatedSelectedPlan();
        }
    }
}
