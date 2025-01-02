<?php

namespace Modules\Settings\Livewire\Companies;

use App\Models\Company\Company;
use Livewire\Component;

class Show extends Component
{
    public Company $company;

    public function mount(Company $company){
        $this->company = $company;
    }

    public function render()
    {
        return view('settings::livewire.companies.show')
        ->extends('layouts.app');
    }
}
