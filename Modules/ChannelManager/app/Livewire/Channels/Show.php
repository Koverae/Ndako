<?php

namespace Modules\ChannelManager\Livewire\Channels;

use Livewire\Component;
use Modules\ChannelManager\Models\Channel\Channel;

class Show extends Component
{
    public Channel $channel;

    public function mount(Channel $channel){
        $this->channel = $channel;
    }

    public function render()
    {
        return view('channelmanager::livewire.channels.show')
        ->extends('layouts.app');
    }
}
