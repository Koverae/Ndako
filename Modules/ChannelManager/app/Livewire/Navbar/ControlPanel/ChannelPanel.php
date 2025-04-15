<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;


class ChannelPanel extends ControlPanel
{
    public $channel;

    public function mount($channel = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        // $this->new = route('properties.create');
        if($channel){
            $this->showIndicators = true;
            $this->channel = $channel;
            $this->isForm = true;
            $this->currentPage = $channel->name;
        }else{
            $this->currentPage = "Channels";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
            // SwitchButton::make('map',"switchView('map')", icon: "bi-map"),
        ];
    }

}
