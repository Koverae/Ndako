<?php

namespace Modules\ChannelManager\Livewire\BookingInvoices;

use Livewire\Component;
use Modules\ChannelManager\Models\Booking\BookingInvoice;

class Show extends Component
{
    public BookingInvoice $invoice;

    public function mount(BookingInvoice $invoice){
        $this->invoice = $invoice;
    }

    public function render()
    {
        return view('channelmanager::livewire.booking-ivoices.show')
        ->extends('layouts.app');
    }
}
