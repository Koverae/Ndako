<?php

namespace Modules\Properties\Livewire\UnitTypes;

use Livewire\Component;
use Modules\Properties\Models\Property\PropertyUnitType;

class Show extends Component
{
    public PropertyUnitType $type;

    public function mount(PropertyUnitType $type){
        $this->type = $type;
    }
    public function render()
    {
        return view('properties::livewire.unit-types.show')
        ->extends('layouts.app');
    }
}
