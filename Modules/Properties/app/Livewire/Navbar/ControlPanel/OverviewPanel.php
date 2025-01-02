<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;

class OverviewPanel extends ControlPanel
{

    public function mount($company = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->new = Route::subdomainRoute('properties.index');
        $this->currentPage = "Overview";

    }

}
