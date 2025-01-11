<?php

namespace Modules\Properties\Livewire\PropertyType;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('properties::livewire.property-type.create')
        ->extends('layouts.app');
    }
}
