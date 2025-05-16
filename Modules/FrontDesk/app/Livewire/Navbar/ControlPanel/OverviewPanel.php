<?php

namespace Modules\FrontDesk\Livewire\Navbar\ControlPanel;

use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class OverviewPanel extends ControlPanel
{

    public function mount($company = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->new = route('properties.index');
        $this->currentPage = "Overview";

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            // SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            // SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
            SwitchButton::make('map',"switchView('map')", "bi-kanban"),
        ];
    }
}
