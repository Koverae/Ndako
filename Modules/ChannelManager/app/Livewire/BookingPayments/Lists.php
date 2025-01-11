<?php

namespace Modules\ChannelManager\Livewire\BookingPayments;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.booking-payments.lists')
        ->extends('layouts.app');
    }
}
