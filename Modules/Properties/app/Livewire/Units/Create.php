<?php

namespace Modules\Properties\Livewire\Units;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('properties::livewire.units.create')
        ->extends('layouts.app');
    }
}
