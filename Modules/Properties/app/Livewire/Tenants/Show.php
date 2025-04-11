<?php

namespace Modules\Properties\Livewire\Tenants;

use Livewire\Component;
use Modules\Properties\Models\Tenant\Tenant;

class Show extends Component
{
    public Tenant $tenant;

    public function mount(Tenant $tenant){
        $this->tenant = $tenant;
    }

    public function render()
    {
        return view('properties::livewire.tenants.show')
        ->extends('layouts.app');
    }
}
