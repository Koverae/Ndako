<?php

namespace Modules\ChannelManager\Livewire\Modal;

use Illuminate\Support\Facades\Http;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Livewire\WithFileUploads;
use Modules\App\Services\GuestCommunicationService;
use Modules\App\Services\PaymentGateway\PaystackService;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\RevenueManager\Models\Accounting\Journal;

class GuestBookingModal extends ModalComponent
{
    use WithFileUploads;
    public Booking $booking;
    public $photo, $image_path, $paymentMethod = 'm-pesa', $paymentAmount = 0, $dueAmount = 0;
    public $paymentVerified = false;

    private BookingService $bookingService;
    private PaystackService $paystackService;
    private GuestCommunicationService $guestCommunicationService;

    public function boot(BookingService $bookingService, PaystackService $paystackService, GuestCommunicationService $guestCommunicationService){
        $this->bookingService = $bookingService;
        $this->paystackService = $paystackService;
        $this->guestCommunicationService = $guestCommunicationService;
    }

    public function rules()
    {
        return [
            'paymentAmount' => ['required', 'numeric', 'min:1', 'max:' . $this->dueAmount],
        ];
    }

    public function mount($booking){
        $this->booking = $booking;
        $this->image_path = $booking->guest->avatar;
        $this->dueAmount = $booking->due_amount;
        // $this->paymentAmount = $this->dueAmount;
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.guest-booking-modal');
    }

    public function checkIn()
    {

        $this->bookingService->checkInBooking($this->booking);
        $this->closeModal();
        // Redirect to the booking calendar
        return $this->redirect(route('bookings.lists', true));

    }

    public function checkOut()
    {
        $this->bookingService->checkOutBooking($this->booking);
        $this->closeModal();
        $this->redirect(route('bookings.lists'), true);

    }

    public function cancelBooking(){
        $this->bookingService->cancelBooking($this->booking);
        $this->closeModal();
        $this->redirect(route('bookings.lists'), true);
    }

    public function addPayment(){
        $this->validate();
        $dueAmountAfterPayment = $this->booking->due_amount - $this->paymentAmount;

        if ($dueAmountAfterPayment < 0) {
            session()->flash('message', "Invalid payment! You’re trying to overpay. The maximum payable amount is " . format_currency($this->booking->due_amount) . ".");
            // session()->flash('error', "Invalid payment! You’re trying to overpay. The maximum payable amount is " . format_currency($this->booking->due_amount) . ".");
            return redirect()->back();
        }

        $extraData = [
            'invoiceId' => $this->booking->invoice->id,
            'method' => $this->paymentMethod,
            'bookingId' => $this->booking->id
        ];

        if($this->paymentMethod == 'paystack'){
            $responseData = $this->paystackService->initializePayment($this->booking->guest->name, $this->booking->guest->email, $this->paymentAmount, $extraData);
            return $this->dispatch('openPaystackPopup', $responseData->data->authorization_url);
            // return $this->dispatch('openPaystackTab', $responseData->data->authorization_url);
        }

        $journal = Journal::isCompany(current_company()->id)->isType($this->paymentMethod)->first();
        $payment = BookingPayment::create([
            'company_id' => current_company()->id,
            'booking_invoice_id' => $this->booking->invoice->id,
            'journal_id' => $journal->id,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->paymentAmount,
            'date' => now(),
            'note' => 'Payment Received for Invoice #'. $this->booking->invoice->reference,
            'type' => 'credit',
        ]);
        $payment->save();

        $due_amount = $this->booking->invoice->due_amount - $payment->amount;

        if ($due_amount == $this->booking->invoice->total_amount) {
            $payment_status = 'unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'partial';
        } else {
            $payment_status = 'paid';
        }
        $paidAmount = $this->booking->invoice->paid_amount + $payment->amount;

        $this->booking->invoice->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);

        $this->booking->invoice->booking->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);
        $this->paymentAmount = 0;
        // $this->closeModal();

        // Send Email
        $this->sendEmail($payment);

        // Send success message

        session()->flash('success', 'Your payment has been successfully processed!');
    }

    #[On('paymentCompleted')]
    public function paymentCompleted()
    {
        $reference = session('paystack_payment_reference');
        session()->forget('paystack_payment_reference'); // Destroy session after retrieving
        // Verify payment from Paystack
        $paystackKey = settings()->paystack_secret_key;

        $response = Http::withToken($paystackKey)->get("https://api.paystack.co/transaction/verify/{$reference}");

        $responseData = $response->json();

        if (isset($responseData['data']) && $responseData['data']['status'] === 'success') {
            session()->flash('success', 'Payment successful!');
        } else {
            session()->flash('error', 'Payment failed!');
        }
    }

    #[On('checkPaymentStatus')]
    public function checkPaymentStatus()
    {
        $reference = session('paystack_reference') ?? request()->query('reference');

        if (!$reference) {
            session()->flash('error', 'Payment was not completed.');
            return;
        }

        $paystackKey = config('services.paystack.secret');

        $response = Http::withToken($paystackKey)->get("https://api.paystack.co/transaction/verify/{$reference}");

        $responseData = $response->json();

        if (isset($responseData['data']) && $responseData['data']['status'] === 'success') {
            session()->flash('success', 'Payment successful!');
            $this->paymentVerified = true;
        } else {
            session()->flash('error', 'Payment failed!');
        }
    }

    // Send Email
    public function sendEmail($payment){

        $model = [
            'guest_id' => (int) $this->booking->guest_id, // Ensure integer
            'booking_id' => (int) $this->booking->id, // Ensure integer
        ];

        $subjectReplace = [
            $this->booking->reference,
            current_property()->name ?? 'Hotel',
        ];

        $contentReplace = [
            $this->booking->guest->name ?? 'Arden BOUET',
            format_currency($payment->amount ?? 0),
            $this->booking->reference,
            current_property()->name,
        ];
        $data = [
            'total_amount' => format_currency($payment->amount ?? 0),
            'reference' => $payment->reference,
            'booking_reference' => $this->booking->reference,
            'date' => $payment->date,
            'payment_method' => $payment->payment_method,
            // 'company_phone' => '+254 123 456 789',
        ];

        // Send Payment Receipt Email
        $this->guestCommunicationService->initiateTemplate(10, $model, $subjectReplace, $contentReplace, $data);
    }

}
