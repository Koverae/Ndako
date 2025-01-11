<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class BookingPanel extends ControlPanel
{
    public $booking;

    public function mount($booking = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->new = Route::subdomainRoute('bookings.create');
        if($isForm){
            $this->showIndicators = true;
        }
        if($booking){
            $this->showIndicators = true;
            $this->booking = $booking;
            $this->isForm = true;
            $this->currentPage = $booking->reference;
        }else{
            $this->currentPage = "Bookings";
        }

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
