<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class TenantPanel extends ControlPanel
{
    public $tenant;

    public function mount($tenant = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->generateBreadcrumbs();
        if($isForm){
            $this->showIndicators = true;
        }
        // if(Auth::user()->can('create_tenants')){
        // }
        $this->new = route('tenants.create');
        if($tenant){
            $this->tenant = $tenant;
            $this->isForm = true;
            $this->currentPage = $tenant->name;
        }else{
            $this->currentPage = "Tenants";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
        ];
    }
}
