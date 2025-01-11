<?php

namespace Modules\ChannelManager\Livewire\BookingInvoices;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.booking-ivoices.lists')
        ->extends('layouts.app');
    }
}
