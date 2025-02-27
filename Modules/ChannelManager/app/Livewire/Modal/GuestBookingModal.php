<?php

namespace Modules\ChannelManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
use Modules\ChannelManager\Models\Guest\Guest;
use Livewire\WithFileUploads;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\RevenueManager\Models\Accounting\Journal;

class GuestBookingModal extends ModalComponent
{
    use WithFileUploads;
    public Booking $booking;
    public $photo, $image_path, $paymentMethod = 'm-pesa', $paymentAmount = 0, $dueAmount = 0;

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
        $this->booking->update([
            'check_in_status' => 'checked_in',
            'actual_check_in' => now(),
        ]);

        $this->booking->unit->update([
            'status' => 'occupied'
        ]);
        
        session()->flash('success', 'Guest checked in successfully!');
        $this->closeModal();

    }

    public function checkOut()
    {
        // Check if dueAmount > 0
        if ($this->dueAmount > 0) {
            session()->flash('error', 'Outstanding amount is due for this booking.');
            return;
        }

        $this->booking->update([
            'check_out_status' => 'checked_out',
            'actual_check_out' => now(),
        ]);

        session()->flash('success', 'Guest checked out successfully!');
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
        // $this->mount($this->booking);
        $this->closeModal();
        // Send success message
        session()->flash('message', 'Booking Information Updated successfully!');
    }
}
