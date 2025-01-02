<?php

namespace Modules\Properties\Livewire\Units;

use Livewire\Component;
use Modules\Properties\Models\Property\PropertyUnit;

class Show extends Component
{
    public PropertyUnit $unit;

    public function mount(PropertyUnit $unit){
        $this->unit = $unit;
    }

    public function render()
    {
        return view('properties::livewire.units.show')
        ->extends('layouts.app');
    }
}
