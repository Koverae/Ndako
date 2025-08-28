<?php

namespace Modules\ChannelManager\Livewire\Guests;

use Livewire\Component;
use Modules\ChannelManager\Models\Guest\Guest;

class Show extends Component
{
    public Guest $guest;

    public function mount(Guest $guest){
        $this->guest = $guest;
    }

    public function render()
    {
        return view('channelmanager::livewire.guests.show')
        ->extends('layouts.app');
    }
}
