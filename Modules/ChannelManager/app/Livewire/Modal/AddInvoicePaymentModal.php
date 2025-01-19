<?php

namespace Modules\ChannelManager\Livewire\Modal;

use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Livewire\WithFileUploads;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Booking\BookingPayment;
use Modules\RevenueManager\Models\Accounting\Journal;

class AddInvoicePaymentModal extends ModalComponent
{
    public BookingInvoice $invoice;

    public $journal, $paymentMethod = 'manual', $amount, $date, $note, $type = 'debit', $journals;

    protected $rules = [
        'journal' => 'required|integer',
        'paymentMethod' => 'required|string',
        'amount' => 'required|numeric',
        'date' => 'required',
        'note' => 'nullable|string'
    ];

    public function mount($invoice = null){
        if($invoice){
            $this->invoice = $invoice;
            $this->amount = $invoice->due_amount;
        }

        $this->journals = Journal::isCompany(current_company()->id)->get();
    }

    public function render()
    {
        return view('channelmanager::livewire.modal.add-invoice-payment-modal');
    }

    public function addPayment(){
        $this->validate();

        $payment = BookingPayment::create([
            'company_id' => current_company()->id,
            'booking_invoice_id' => $this->invoice->id,
            'journal_id' => $this->journal,
            'payment_method' => $this->paymentMethod,
            'amount' => $this->amount,
            'date' => $this->date,
            'note' => 'Payment Received for Invoice #'. $this->invoice->reference,
            'type' => $this->type,
            // 'status' => 'posted',
        ]);
        $payment->save();

        $due_amount = $this->invoice->due_amount - $payment->amount;

        if ($due_amount == $this->invoice->total_amount) {
            $payment_status = 'unpaid';
        } elseif ($due_amount > 0) {
            $payment_status = 'partial';
        } else {
            $payment_status = 'paid';
        }
        $paidAmount = $this->invoice->paid_amount + $payment->amount;

        $this->invoice->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);

        $this->invoice->booking->update([
            'payment_status' => $payment_status,
            'paid_amount' => ($paidAmount),
            'due_amount' => ($due_amount),
        ]);

        $this->closeModal();
    }

}
