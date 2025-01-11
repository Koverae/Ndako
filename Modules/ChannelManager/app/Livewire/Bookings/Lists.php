<?php

namespace Modules\ChannelManager\Livewire\Bookings;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.bookings.lists')
        ->extends('layouts.app');
    }
}
