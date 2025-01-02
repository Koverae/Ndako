<?php

namespace Modules\ChannelManager\Livewire\Channels;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('channelmanager::livewire.channels.lists')
        ->extends('layouts.app');
    }
}
