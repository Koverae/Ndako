<?php

namespace Modules\Properties\Livewire\UnitTypes;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('properties::livewire.unit-types.lists')
        ->extends('layouts.app');
    }
}
