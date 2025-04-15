<?php

namespace Modules\Properties\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnitType;

class UnitTypePanel extends ControlPanel
{
    public $type;

    public function mount($type = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();

        $properties = Property::isCompany(current_company()->id)
        ->pluck('name', 'id') // now it's an array like [1 => 'House', 2 => 'Apartment']
        ->toArray();

        $this->filterTypes = [
            'property_id' => $properties,
        ];

        $this->new = route('properties.unit-types.create');
        if($isForm){
            $this->showIndicators = true;
        }
        if($type){
            $this->isForm = true;
            $this->type = $type;
            $this->currentPage = $type->name;
        }else{
            $this->currentPage = "Unit Types";
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

        $units = PropertyUnitType::isCompany(current_company()->id)->get();
        $exportColumns = [
            'id',
            'name',
            'created_at',
        ];
        $exportService->exportSelected("Unit_Type_export", $units, $exportColumns);
    }

    public function deleteSelectedItems(){

        PropertyUnitType::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.unit-types.lists'), navigate:true);
    }

    public function duplicateItems(){
        foreach($this->selected as $unit){
            $this->duplicate($unit);
        }

        // Return a success message or the duplicated unit
        LivewireAlert::title('Items duplicated!')
        ->text('Property unit types have been duplicated successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.unit-types.lists'), navigate:true);
    }

    public function duplicate($typeId)
    {
        $type = PropertyUnitType::find($typeId);

        if (!$type) {
            LivewireAlert::title('Property unit type not found!')
                ->text('The property unit type does not exist!')
                ->error()
                ->position('top-end')
                ->timer(4000)
                ->toast()
                ->show();
            return;
        }

        // Duplicate base property
        $newType = $type->replicate();
        $newType->name = $type->name . ' (copy)';
        // $newType->slug = null; // in case you're using unique slugs
        $newType->save(); // Save early so we have an ID for relations

        // Duplicate Prices
        foreach ($type->prices as $price) {
            $newType->prices()->create([
                'company_id' => current_company()->id,
                'property_id' => $type->property_id,
                'property_unit_type_id' => $newType->id,
                'lease_term_id' => $price->lease_term_id,
                'name' => $newType->name . ' ' . lease_term($price->lease_term_id)->name,
                'price' => $price->price,
                'is_default' => $price->is_default,
            ]);
        }

        // Duplicate Features
        foreach ($type->features as $feature) {
            $newType->features()->create([
                'company_id' => current_company()->id,
                'property_unit_type_id' => $newType->id,
                'feature_id' => $feature->feature_id,
            ]);
        }

    }

}
