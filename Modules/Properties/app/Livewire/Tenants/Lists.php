<?php

namespace Modules\Properties\Livewire\Tenants;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('properties::livewire.tenants.lists')
        ->extends('layouts.app');
    }
}
