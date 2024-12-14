<?php

namespace Modules\Settings\Livewire\Users;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('settings::livewire.users.create')->extends('layouts.app');
    }
}
