<?php

namespace Modules\Properties\Livewire\Tenants;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('properties::livewire.tenants.create')
        ->extends('layouts.app');
    }
}
