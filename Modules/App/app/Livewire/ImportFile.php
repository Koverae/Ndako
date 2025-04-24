<?php

namespace Modules\App\Livewire;

use Livewire\Component;

class ImportFile extends Component
{
    public function render()
    {
        return view('app::livewire.import-file')
        ->extends('layouts.app');
    }
}
