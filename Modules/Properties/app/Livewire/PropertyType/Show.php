<?php

namespace Modules\Properties\Livewire\PropertyType;

use Livewire\Component;
use Modules\Properties\Models\Property\PropertyType;

class Show extends Component
{
    public PropertyType $type;

    public function mount(PropertyType $type){
        $this->type = $type;
    }
    public function render()
    {
        return view('properties::livewire.property-type.show')
        ->extends('layouts.app');
    }
}
