<?php

namespace Modules\Settings\Livewire\Companies;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('settings::livewire.companies.lists')->extends('layouts.app');
    }
}
