<?php

namespace Modules\App\Livewire;

use Livewire\Component;

class Onboarding extends Component
{
    public function render()
    {
        return view('app::livewire.onboarding')
        ->extends('layouts.app');
    }
}
