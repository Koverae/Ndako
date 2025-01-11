<?php

namespace Modules\ChannelManager\Livewire\Bookings;

use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;

class Show extends Component
{
    public Booking $booking;

    public function mount(Booking $booking){
        $this->booking = $booking;
    }

    public function render()
    {
        return view('channelmanager::livewire.bookings.show')
        ->extends('layouts.app');
    }
}
