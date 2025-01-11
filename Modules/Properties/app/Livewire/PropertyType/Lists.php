<?php

namespace Modules\Properties\Livewire\PropertyType;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('properties::livewire.property-type.lists')
        ->extends('layouts.app');
    }
}
