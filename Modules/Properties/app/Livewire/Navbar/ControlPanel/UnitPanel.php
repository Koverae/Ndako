<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class UnitPanel extends ControlPanel
{
    public $unit;

    public function mount($unit = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->new = Route::subdomainRoute('properties.units.create');
        if($unit){
            $this->showIndicators = true;
            $this->unit = $unit;
            $this->isForm = true;
            $this->currentPage = $unit->name;
        }else{
            $this->currentPage = "Units";
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
