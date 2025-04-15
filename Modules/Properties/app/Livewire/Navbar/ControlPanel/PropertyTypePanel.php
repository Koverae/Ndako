<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class PropertyTypePanel extends ControlPanel
{
    public $type;

    public function mount($type = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->new = route('properties.types.create');
        if($isForm){
            $this->showIndicators = true;
        }
        if($type){
            $this->isForm = true;
            $this->type = $type;
            $this->currentPage = $type->name;
        }else{
            $this->currentPage = "Property Types";
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
