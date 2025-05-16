<?php

namespace Modules\FrontDesk\Livewire\Interface;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('frontdesk::livewire.interface.home')
        ->extends('layouts.pos');
    }
}
