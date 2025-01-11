<?php

namespace Modules\ChannelManager\Livewire\Guests;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.guests.lists')
        ->extends('layouts.app');
    }
}
