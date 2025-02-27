<?php

namespace Modules\Settings\Livewire\Roles;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('settings::livewire.roles.lists')->extends('layouts.app');
    }
}
