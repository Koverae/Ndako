<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class BookingInvoicePanel extends ControlPanel
{
    public $invoice;

    public function mount($invoice = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->showIndicators = true;
        $this->invoice = $invoice;
        $this->isForm = true;
        $this->currentPage = $invoice->reference;

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
            SwitchButton::make('map',"switchView('map')", icon: "bi-calendar"),
            // SwitchButton::make('map',"switchView('map')", icon: "bi-map"),
        ];
    }
}
