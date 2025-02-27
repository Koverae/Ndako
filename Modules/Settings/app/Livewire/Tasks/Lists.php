<?php

namespace Modules\Settings\Livewire\Tasks;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('settings::livewire.tasks.lists')->extends('layouts.app');
    }
}
