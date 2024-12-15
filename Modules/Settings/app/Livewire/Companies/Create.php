<?php

namespace Modules\Settings\Livewire\Companies;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('settings::livewire.companies.create')->extends('layouts.app');
    }
}
