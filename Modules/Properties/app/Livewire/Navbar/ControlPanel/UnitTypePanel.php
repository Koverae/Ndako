<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class UnitTypePanel extends ControlPanel
{
    public $type;

    public function mount($type = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->new = route('properties.unit-types.create');
        if($isForm){
            $this->showIndicators = true;
        }
        if($type){
            $this->isForm = true;
            $this->type = $type;
            $this->currentPage = $type->name;
        }else{
            $this->currentPage = "Unit Types";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            // SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
        ];
    }
}
