<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class GuestPanel extends ControlPanel
{

    public function mount($isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        // $this->showIndicators = true;
            $this->currentPage = "Guests";

        $this->filterTypes = [
            'type' => [
                'individual' => 'individual',    // string filter
                'company' => 'corporate',      // string filter
                'agent' => 'agent'               // string filter
            ],
            'status' => [
                1 => 'active',    // int filter for active status (1 = active, 0 = inactive)
                0 => 'inactive',  // int filter for inactive status (1 = active, 0 = inactive)
            ],
        ];
    }

    public function actionButtons(): array
    {
        return [
            // ActionButton::make('export', 'Export All', 'exportAll', false, "fas fa-download"),
            ActionButton::make('import', 'Import Records', 'importRecords', false, "fas fa-upload"),
        ];
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

    public function importRecords(){
        return $this->redirect(route('import.records', 'mod_guests'), true);
    }

    public function deleteSelectedItems(){

        PropertyUnit::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.units.lists'), navigate:true);
    }
}
