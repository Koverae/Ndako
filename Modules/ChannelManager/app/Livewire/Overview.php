<?php

namespace Modules\ChannelManager\Livewire;

use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.overview')
        ->extends('layouts.app');
    }
}
