<?php

namespace Modules\Properties\Livewire\Units;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('properties::livewire.units.lists')
        ->extends('layouts.app');
    }
}
