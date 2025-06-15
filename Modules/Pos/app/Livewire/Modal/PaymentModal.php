<?php

namespace Modules\Pos\Livewire\Modal;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\App\Services\PaymentGateway\PaystackService;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Pos\Models\Order\PosOrderPayment;
use Modules\RevenueManager\Models\Accounting\Journal;

class PaymentModal extends ModalComponent
{
    public PosOrder $order;
    private PaystackService $paystackService;
    public string $tab = 'offline';
    public ?string $offlineMethod = null;
    public ?string $paymentMethod = null;
    public float $amount = 0;
    public string $reference;

    public function mount(PosOrder $order){
        $this->order = $order;
    }

    public function boot(PaystackService $paystackService){
        $this->paystackService = $paystackService;
    }

    public function initiatePaystack(){

        $extraData = [
            'method' => 'paystack',
            'orderId' => $this->order->id,
            'source' => 'pos',
        ];

        $responseData = $this->paystackService->initializePayment($this->order->guest->name ?? 'Brian Mwangi', $this->order->guest->email ?? 'brianmwangi@gmail.com', $this->order->total_amount, $extraData);
        return $this->dispatch('openPaystackPopup', $responseData->data->authorization_url);
        // return $this->dispatch('openPaystackTab', $responseData->data->authorization_url);
    }

    #[On('poPaymentCompleted')]
    public function paymentCompleted($reference)
    {
        $reference = session('paystack_payment_reference');
        session()->forget('paystack_payment_reference'); // Destroy session after retrieving
        // Verify payment from Paystack
        $paystackKey = settings()->paystack_secret_key;

        $response = Http::withToken($paystackKey)->get("https://api.paystack.co/transaction/verify/{$reference}");

        $responseData = $response->json();

        if (isset($responseData['data']) && $responseData['data']['status'] === 'success') {
            Log::info('Payment successful', ['reference' => $reference, 'amount' => $responseData['data']['amount']  / 100 ]);
            session()->flash('success', 'Payment successful!');
        } else {
            session()->flash('error', 'Payment failed!');
        }

        // $this->handlePayment([
        //     'order_id' => $this->order->id,
        //     'payment_method' => 'paystack',
        //     'reference' => $reference,
        //     'amount' => $responseData['data']['amount'] / 100, // Convert to actual amount
        //     'method' => 'paystack',
        //     'source' => 'pos',
        // ]);
        $this->dispatch('posOrderPaymentCompleted', [
            'orderId' => $this->order->id,
            'reference' => $reference,
            'amount' => $responseData['data']['amount'] / 100, // Convert to actual amount
            'method' => $responseData['data']['metadata']['method'] ?? 'paystack',
            'source' => $responseData['data']['metadata']['source'] ?? 'pos',
        ]);
        $this->order->payment_status = true;
        $this->order->save();
        $this->dispatch('closeModal');
    }

    public function processOfflinePayment()
    {
        $this->validate([
            'offlineMethod' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);
        if ($this->amount <= 0) {
            session()->flash('error', 'Amount must be greater than zero.');
            return;
        }
        if (!$this->offlineMethod) {
            session()->flash('error', 'Please select a payment method.');
            return;
        }

        $this->dispatch('posOrderPaymentCompleted', [
            'orderId' => $this->order->id,
            'reference' => $this->reference ?? strtoupper(Str::random(12)), // No reference for offline payments
            'amount' => $this->amount,
            'method' => $this->offlineMethod,
            'source' => 'pos',
        ]);

        $this->order->payment_status = true;
        $this->order->save();
        session()->flash('success', 'Payment recorded successfully!');
        $this->dispatch('closeModal');
    }

    // #[On('checkPaymentStatus')]
    // public function checkPaymentStatus()
    // {
    //     $reference = session('paystack_reference') ?? request()->query('reference');

    //     if (!$reference) {
    //         session()->flash('error', 'Payment was not completed.');
    //         return;
    //     }

    //     $paystackKey = config('services.paystack.secret');

    //     $response = Http::withToken($paystackKey)->get("https://api.paystack.co/transaction/verify/{$reference}");

    //     $responseData = $response->json();

    //     if (isset($responseData['data']) && $responseData['data']['status'] === 'success') {
    //         session()->flash('success', 'Payment successful!');
    //         $this->order->payment_status = 'paid';
    //     } else {
    //         session()->flash('error', 'Payment failed!');
    //     }
    // }

    public function render()
    {
        return view('pos::livewire.modal.payment-modal');
    }
}
