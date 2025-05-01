<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\ChannelManager\Models\Guest\Guest;

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
            ActionButton::make('export', 'Export All', 'exportAll', false, "fas fa-download"),
            ActionButton::make('import', 'Import Records', 'importRecords', false, "fas fa-upload"),
        ];
    }

    public function actionDropdowns(): array
    {
        return [
            ActionDropdown::make('export', 'Export', 'exportSelected', false, "fas fa-download"),
            // ActionDropdown::make('archive', 'Archive', 'archive', false, "fas fa-archive"),
            // ActionDropdown::make('unarchive', 'Unarchive', 'unarchive', false, "fas fa-inbox"),
            // ActionDropdown::make('duplicate', 'Duplicate', 'duplicateItems', false, "fas fa-copy"),
            ActionDropdown::make('delete', 'Delete', 'deleteSelectedItems', false, "fas fa-trash", true, "Do you really want to delete the selected items?"),
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

    public function exportAll(){
        $exportService = new ReportExportService();

        $guests = Guest::isCompany(current_company()->id)->get()
        ->map(function ($guest) {

            return [
                'name' => $guest->name,
                'email' => $guest->email,
                'phone' => $guest->phone,
                'job' => $guest->job,
                'type' => $guest->type,
                'identity_proof' => $guest->identity_proof,
                'identity' => $guest->identity,
                'address' => $guest->street,
                'city' => $guest->city,
                'country' => $guest->country->common_name ?? "N/A",
            ];
        });

        $detailedSections = [
            'Guests' => $guests,
        ];

        return $exportService->export("Guest_export", [], $detailedSections);
    }

    public function exportSelected(){
        $exportService = new ReportExportService();

        $guests = Guest::isCompany(current_company()->id)->get()
        ->map(function ($guest) {

            return [
                'name' => $guest->name,
                'email' => $guest->email,
                'phone' => $guest->phone,
                'job' => $guest->job,
                'type' => $guest->type,
                'identity_proof' => $guest->identity_proof ?? "N/A",
                'identity' => $guest->identity ?? "N/A",
                'address' => $guest->street,
                'city' => $guest->city,
                'country' => $guest->country->common_name ?? "N/A",
            ];
        });

        $detailedSections = [
            'Guests' => $guests,
        ];

        return $exportService->export("Guest_export", [], $detailedSections);
    }

    public function deleteSelectedItems(){

        Guest::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('guests.lists'), navigate:true);
    }
}
