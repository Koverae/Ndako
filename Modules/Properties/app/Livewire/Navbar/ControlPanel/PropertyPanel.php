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
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;

class PropertyPanel extends ControlPanel
{
    public $property;

    public function mount($property = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->new = route('properties.create');

        $types = PropertyType::isCompany(current_company()->id)
        ->whereIn('property_type_group', ['residential', 'hospitality'])
        ->pluck('name', 'id') // now it's an array like [1 => 'House', 2 => 'Apartment']
        ->toArray();

        $this->filterTypes = [
            'property_type_group' => [
                'residential' => 'residential',    // string filter
                'hospitality' => 'hospitality',      // string filter
                'commercial' => 'commercial'               // string filter
            ],
            'property_type_id' => $types,
            'status' => [
                'active' => 'active',
                'inactive' => 'inactive',
                'under-maintenance' => 'under-maintenance',
            ],
        ];

        if($property){
            $this->showIndicators = true;
            $this->property = $property;
            $this->isForm = true;
            $this->currentPage = $property->name;
        }else{
            $this->currentPage = "Properties";
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
            SwitchButton::make('map',"switchView('map')", "bi-map"),
        ];
    }

    public function export(ReportExportService $exportService){

        $units = Property::isCompany(current_company()->id)->get();
        $exportColumns = [
            'id',
            'name',
            'created_at',
        ];
        $exportService->exportSelected("Property_export", $units, $exportColumns);
    }

    public function deleteSelectedItems(){

        Property::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.lists'), navigate:true);
    }

    public function duplicateItems(){
        foreach($this->selected as $unit){
            $this->duplicate($unit);
        }

        // Return a success message or the duplicated unit
        LivewireAlert::title('Items duplicated!')
        ->text('Properties have been duplicated successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('properties.lists'), navigate:true);
    }

    public function duplicate($propertyId)
    {
        $property = Property::find($propertyId);

        if (!$property) {
            LivewireAlert::title('Property not found!')
                ->text('The property does not exist!')
                ->error()
                ->position('top-end')
                ->timer(4000)
                ->toast()
                ->show();
            return;
        }

        // Duplicate base property
        $newProperty = $property->replicate();
        $newProperty->name = $property->name . ' (copy)';
        // $newProperty->slug = null; // in case you're using unique slugs
        $newProperty->save(); // Save early so we have an ID for relations

        // Duplicate Floors
        foreach ($property->floors as $floor) {
            $newProperty->floors()->create([
                'company_id' => current_company()->id,
                'name' => $floor->name,
                'description' => $floor->description,
            ]);
        }

        // Duplicate Amenities
        foreach ($property->propertyAmenities as $amenity) {
            $newProperty->propertyAmenities()->create([
                'company_id' => current_company()->id,
                'amenity_id' => $amenity->amenity_id,
            ]);
        }

        // Duplicate Unit Types + Prices + Units
        foreach ($property->unitTypes as $unitType) {
            $newType = $newProperty->unitTypes()->create([
                'company_id' => current_company()->id,
                'name' => $unitType->name,
                'description' => $unitType->description,
                'price' => $unitType->price,
                'capacity' => $unitType->capacity,
                'size' => $unitType->size,
            ]);

            foreach ($unitType->prices as $price) {
                PropertyUnitTypePricing::create([
                    'company_id' => current_company()->id,
                    'property_id' => $newProperty->id,
                    'property_unit_type_id' => $newType->id,
                    'lease_term_id' => $price->lease_term_id,
                    'name' => $newType->name . ' ' . lease_term($price->lease_term_id)->name,
                    'price' => $price->price,
                    'is_default' => $price->is_default,
                ]);
            }

            foreach ($unitType->units as $unit){
                PropertyUnit::create([
                    'company_id' => current_company()->id,
                    'property_id' => $newProperty->id,
                    'floor_id' => $unit->floor_id,
                    'property_unit_type_id' => $newType->id,
                    'name' => $unit->name,
                ]);
            }
        }
    }


}
