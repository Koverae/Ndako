<?php

namespace Modules\Properties\Livewire\Properties;

use Livewire\Component;
use Modules\Properties\Models\Property\Property;

class Show extends Component
{
    public Property $property;

    public function mount(Property $property){
        $this->property = $property;
    }

    public function render()
    {
        return view('properties::livewire.properties.show')
        ->extends('layouts.app');
    }
}
