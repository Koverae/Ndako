<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\Properties\Models\Property\PropertyUnit;

class UnitPanel extends ControlPanel
{
    public $unit;

    public function mount($unit = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        // dd($this->breadcrumbs);
        if(Auth::user()->can('create_units')){
            $this->new = route('properties.units.create');
        }
        if($unit){
            $this->showIndicators = true;
            $this->unit = $unit;
            $this->isForm = true;
            $this->currentPage = $unit->name;
        }else{
            $this->currentPage = "Units";
        }

    }

    public function actionDropdowns(): array
    {
        return [
            ActionDropdown::make('export', 'Export', 'export', false, "fas fa-download"),
            ActionDropdown::make('archive', 'Archive', 'archive', false, "fas fa-archive"),
            ActionDropdown::make('unarchive', 'Unarchive', 'unarchive', false, "fas fa-inbox"),
            ActionDropdown::make('duplicate', 'Duplicate', 'duplicateItems', false, "fas fa-copy"),
            ActionDropdown::make('delete', 'Delete', 'deleteSelectedItems', false, "fas fa-trash", true, "Do you really want to delete the selected items?"),
        ];
    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
            // SwitchButton::make('kanban',"switchView('kanban')", "bi-kanban"),
        ];
    }

    public function export(ReportExportService $exportService){

        $units = PropertyUnit::isCompany(current_company()->id)->get();
        $exportColumns = [
            'id',
            'name',
            'created_at',
        ];
        $exportService->exportSelected("Unit_export", $units, $exportColumns);
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

    public function duplicateItems(){
        foreach($this->selected as $unit){
            $this->duplicate($unit);
        }

        // Return a success message or the duplicated unit
        LivewireAlert::title('Items duplicated!')
        ->text('Unit duplicated successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.units.lists'), navigate:true);
    }

    public function duplicate($unitId)
    {
        // Find the unit by its ID
        $unit = PropertyUnit::find($unitId);

        // Check if the unit exists
        if (!$unit) {
            LivewireAlert::title('Unit not found!')
            ->text('The unit does not exist!')
            ->error()
            ->position('top-end')
            ->timer(4000)
            ->toast()
            ->show();
        }

        // Create a new unit with the same attributes (excluding the primary key)
        $newUnit = $unit->replicate();

        // Optionally, you can adjust attributes before saving the duplicated unit
        // For example, if you want to modify certain fields like `name`, `slug`, etc.
        $newUnit->name = $unit->name. ' (copy)'; // You can assign new values here if needed

        // Save the new unit to the database
        $newUnit->save();

    }

}
