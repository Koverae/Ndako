<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class BookingPaymentPanel extends ControlPanel
{

    public function mount($invoice = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->filterTypes = [
            'status' => [
                'pending' => 'Pending',    // string filter
                'posted' => 'Posted',
            ],
        ];
        // $this->showIndicators = true;
            $this->currentPage = "Payments";

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
        ];
    }

}
