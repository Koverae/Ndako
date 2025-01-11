<?php

namespace Modules\ChannelManager\Livewire\Bookings;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.bookings.create')
        ->extends('layouts.app');
    }
}
