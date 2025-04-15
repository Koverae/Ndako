<?php

namespace Modules\Settings\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class RolePermissionPanel extends ControlPanel
{
    public $role;

    public function mount($role = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        // $this->new = route('settings.companies.create');
        if($role){
            $this->showIndicators = true;
            $this->role = $role;
            $this->isForm = true;
            $this->currentPage = $role->name;
        }else{
            $this->currentPage = "Roles";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
        ];
    }
}
