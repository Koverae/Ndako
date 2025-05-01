<?php

namespace Modules\ChannelManager\Livewire\Navbar\ControlPanel;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\ChannelManager\Models\Booking\Booking;

class BookingPanel extends ControlPanel
{
    public $booking;

    public function mount($booking = null, $isForm = false)
    {
        $this->view_type = 'calendar';
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        if(Auth::user()->can('create_reservations')){
            $this->new = route('bookings.create');
        }
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
            ActionDropdown::make('duplicate', 'Duplicate', 'duplicateItems', false, "fas fa-copy"),
            ActionDropdown::make('delete', 'Delete', 'deleteSelectedItems', false, "fas fa-trash", true, "Do you really want to delete the selected items?"),
        ];
    }


    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('calendar',"switchView('calendar')", icon: "bi-calendar"),
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            // SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
        ];
    }

    public function importRecords(){
        return $this->redirect(route('import.records', 'mod_bookings'), true);
    }

    public function exportSelected(){
        $exportService = new ReportExportService();

        $bookings = Booking::isCompany(current_company()->id)
        ->whereIn('id', $this->selected)->get()
        ->map(function ($booking) {

            return [
                'property' => $booking->unit->property->name,
                'room' => $booking->unit->name,
                'source' => $booking->source,
                'reference' => $booking->reference,
                'guest' => $booking->guest->name,
                'check_in' => Carbon::parse($booking->check_in)->format('d/m/Y'),
                'check_out' => Carbon::parse($booking->check_out)->format('d/m/Y'),
                'no_guests' => $booking->guests,
                'status' => $booking->status,
                'paid_amount' => format_currency($booking->paid_amount),
                'due_amount' => format_currency($booking->due_amount),
                'total_amount' => format_currency($booking->total_amount),
            ];
        });

        $detailedSections = [
            'Bookings' => $bookings,
        ];

        return $exportService->export("Bookings_export", [], $detailedSections);
    }

    public function exportAll(){
        $exportService = new ReportExportService();

        $bookings = Booking::isCompany(current_company()->id)->get()
        ->map(function ($booking) {

            return [
                'property' => $booking->unit->property->name,
                'room' => $booking->unit->name,
                'source' => $booking->source,
                'reference' => $booking->reference,
                'guest' => $booking->guest->name,
                'check_in' => Carbon::parse($booking->check_in)->format('d/m/Y'),
                'check_out' => Carbon::parse($booking->check_out)->format('d/m/Y'),
                'no_guests' => $booking->guests,
                'status' => $booking->status,
                'paid_amount' => format_currency($booking->paid_amount),
                'due_amount' => format_currency($booking->due_amount),
                'total_amount' => format_currency($booking->total_amount),
            ];
        });

        $detailedSections = [
            'Bookings' => $bookings,
        ];

        return $exportService->export("Bookings_export", [], $detailedSections);
    }

    public function deleteSelectedItems(){

        Booking::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('bookings.lists'), navigate:true);
    }
}
