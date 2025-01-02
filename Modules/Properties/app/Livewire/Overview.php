<?php

namespace Modules\Properties\Livewire;

use Livewire\Component;

class Overview extends Component
{
    public function render()
    {
        return view('properties::livewire.overview')
        ->extends('layouts.app');
    }
}
