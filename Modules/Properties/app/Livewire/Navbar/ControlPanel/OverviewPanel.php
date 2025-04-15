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
        $this->generateBreadcrumbs();
        $this->new = route('properties.index');
        $this->currentPage = "Overview";

    }

}
