<?php

namespace Modules\Properties\Livewire\Properties;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('properties::livewire.properties.create')
        ->extends('layouts.app');
    }
}
