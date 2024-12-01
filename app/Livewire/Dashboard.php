<?php

namespace App\Livewire;

use App\Models\Module\Module;
use Livewire\Component;

class Dashboard extends Component
{
    public $test= "Dashboard";

    public function render()
    {
        return view('livewire.dashboard')
            ->extends('layouts.app');
    }

    public function openApp($module){
        // Retrieve the current array from the cache
        $module = Module::find($module);
        update_menu($module->navbar_id);

        return $this->redirect(route($module->link, ['subdomain' => current_company()->domain_name, 'menu' => current_menu()]), navigate: true);
    }
}