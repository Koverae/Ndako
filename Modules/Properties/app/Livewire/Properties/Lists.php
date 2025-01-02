<?php

namespace Modules\Properties\Livewire\Properties;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('properties::livewire.properties.lists')
        ->extends('layouts.app');
    }
}
