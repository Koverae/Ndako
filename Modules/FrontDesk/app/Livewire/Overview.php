<?php

namespace Modules\FrontDesk\Livewire;

use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        return view('frontdesk::livewire.overview')
        ->extends('layouts.app');
    }
}
