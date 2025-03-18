<?php

namespace Modules\ChannelManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Livewire\WithFileUploads;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\RevenueManager\Models\Accounting\Journal;

class GuestBookingModal extends ModalComponent
{
    use WithFileUploads;
    public Booking $booking;
    public $photo, $image_path, $paymentMethod = 'm-pesa', $paymentAmount = 0, $dueAmount = 0;

    private BookingService $bookingService;

    public function boot(BookingService $bookingService){
        $this->bookingService = $bookingService;
    }

    public function rules()
    {
        return [
            'paymentAmount' => ['required', 'numeric', 'max:' . $this->dueAmount],
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

    }

    public function addPayment(){
        $this->validate();

        $journal = Journal::isCompany(current_company()->id)->isType($this->paymentMethod)->first();
        $payment = BookingPayment::create([
            'company_id' => current_company()->id,
            'booking_invoice_id' => $this->booking->invoice->id,
            'journal_id' => $journal->id,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->paymentAmount,
            'date' => now(),
            'note' => 'Payment Received for Invoice #'. $this->booking->invoice->reference,
            'type' => 'debit',
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
        // Send success message
        session()->flash('message', 'Your payment has been successfully processed!');
    }
}
