<?php

namespace Modules\Properties\Livewire\UnitTypes;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('properties::livewire.unit-types.create')
        ->extends('layouts.app');
    }
}
