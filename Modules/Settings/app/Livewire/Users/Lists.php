<?php

namespace Modules\Settings\Livewire\Users;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('settings::livewire.users.lists')->extends('layouts.app');
    }
}
