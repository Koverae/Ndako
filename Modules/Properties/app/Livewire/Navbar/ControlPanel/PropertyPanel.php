<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class PropertyPanel extends ControlPanel
{
    public $property;

    public function mount($property = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->new = Route::subdomainRoute('properties.create');
        if($property){
            $this->showIndicators = true;
            $this->property = $property;
            $this->isForm = true;
            $this->currentPage = $property->name;
        }else{
            $this->currentPage = "Properties";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
            SwitchButton::make('map',"switchView('map')", icon: "bi-map"),
        ];
    }

}
