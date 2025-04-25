<?php

namespace Modules\App\Livewire\Navbar\ControlPanel;

// use Modules\App\Livewire\Components\Navbar\ControlPanel;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;

class ImportPanel extends ControlPanel
{

    public $unit;
    public $type;

    public function mount($unit = null, $isForm = false, $type = null)
    {
        $this->isForm = true;
        $this->currentPage = "Import a file";
    }

    public function exportAll(){
        $exportService = new ReportExportService();

        $units = PropertyUnit::isCompany(current_company()->id)->get()
        ->map(function ($unit) {

            return [
                'unit_name' => $unit->name,
                'unit_type' => $unit->unitType->name,
                'unit_floor' => $unit->floor->name ?? "N/A",
                'unit_status' => $unit->status,
            ];
        });

        $detailedSections = [
            'Units' => $units,
        ];

        return $exportService->export("Unit_export", [], $detailedSections);
    }

    public function exportSelected(){
        $exportService = new ReportExportService();

        $units = PropertyUnit::isCompany(current_company()->id)
        ->whereIn('id', $this->selected)->get()
        ->map(function ($unit) {

            return [
                'unit_name' => $unit->name,
                'unit_type' => $unit->unitType->name,
                'unit_floor' => $unit->floor->name ?? "N/A",
                'unit_status' => $unit->status,
            ];
        });

        $detailedSections = [
            'Units' => $units,
        ];

        return $exportService->export("Unit_export", [], $detailedSections);
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
