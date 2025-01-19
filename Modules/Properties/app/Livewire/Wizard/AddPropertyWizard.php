<?php

namespace Modules\Properties\Livewire\Wizard;

use Livewire\Attributes\On;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Wizard\SimpleWizard;
use Modules\App\Livewire\Components\Wizard\Step;
use Modules\App\Livewire\Components\Wizard\StepPage;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnitType;
use Illuminate\Support\Facades\DB;
use Modules\Properties\Models\Property\PropertyAmenity;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyUnit;

class AddPropertyWizard extends SimpleWizard
{
    // Property
    public $category = '', $type, $invoicing = 'rate', $name, $country, $street, $city, $state, $zip, $description, $floors = 0;
    public array $selectedAmenity = [], $propertyFloors = [], $propertyUnits = [];
    
    // Unit
    public $unitName, $numberUnits = 1, $capacity = 1, $unitType, $unitSize = 0, $unitDesc, $unitPrice = 0;
    public array $unitFeatures = [], $units = [];

    protected $rules = [
        'name' => 'required|string|max:30',
        'selectedAmenity' => 'array|min:1', // Ensure at least one amenity is selected
        'selectedAmenity.*' => 'exists:amenities,id', // Validate amenity IDs
    ];

    public function mount(){
        $this->unitType = PropertyUnitType::isCompany(current_company()->id)->get();
        $this->type = PropertyType::where('slug', 'hotels')->isCompany(current_company()->id)->first();
    }

    public array $propertyType = [
        'hotel' => [
            [
                'name' => 'Hotel',
                'description' => 'Accommodations for travellers often offering restaurants, meeting rooms and other guest services.',
                'slug' => 'hotel'
            ],
            [
                'name' => 'Hostel',
                'description' => 'Budget accommodation with mostly dorm-style bedding ad social atmosphere.',
                'slug' => 'hostel'
            ],
            [
                'name' => 'Aparthotel',
                'description' => 'A self-catering apartment with some hotel facilities like a reception desk.',
                'slug' => 'aparthotel'
            ],
            [
                'name' => 'Boutique Hotel',
                'description' => 'A small, stylish, and often luxurious hotel that offers personalized services and a unique experience.',
                'slug' => 'boutique-hotel'
            ],
        ],
    ];

    public function steps(){
        return [
            Step::make(0, 'Welcome', true),
            Step::make(1, 'Pick your place', false),
            Step::make(2, 'Does this sound like your property?', false),
            Step::make(3, 'What can guests use at your hotel?', false),
            Step::make(4, 'Ready to Finalize Everything?', false),
        ];
    }

    public function stepPages(){
        return [
            StepPage::make('welcome', '', 0)->component('app::wizard.step-page.special.property.welcome'),
            StepPage::make('category', '', 1)->component('app::wizard.step-page.special.property.pick-category'),
            StepPage::make('basic-data', '', 2)->component('app::wizard.step-page.special.property.property-basic-info'),
            StepPage::make('unit-detail', '', 3)->component('app::wizard.step-page.special.property.property-unit-details'),
            StepPage::make('confirmation', '', 4)->component('app::wizard.step-page.special.property.confirmation'),
        ];
    }
    
    public function pickCategory($category){
        $this->category = $category;
        $this->goToNextStep();
    }

    // Add the unit to the propertyUnits array
    public function addUnit()
    {
        // Validate the form data before adding
        $this->validate([
            'unitName' => 'required|string',
            'unitDesc' => 'nullable|string',
            'numberUnits' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'unitSize' => 'nullable|numeric|min:1',
        ]);

        // Add the current unit data to the propertyUnits array
        $this->propertyUnits[] = [
            'unitName' => $this->unitName,
            'unitDesc' => $this->unitDesc,
            'numberUnits' => $this->numberUnits,
            'price' => $this->unitPrice,
            'capacity' => $this->capacity,
            'unitSize' => $this->unitSize,
            'unitFeatures' => $this->unitFeatures,
            'units' => $this->units,
        ];

        // Optionally, reset the form fields for the next entry
        $this->unitFeatures = []; 
        $this->capacity = 1;
        $this->reset(['unitName', 'unitDesc', 'numberUnits', 'capacity', 'unitSize', 'unitFeatures', 'unitPrice']);
    }

    public function removeUnit($index)
    {
        unset($this->propertyUnits[$index]);
        $this->propertyUnits = array_values($this->propertyUnits); // Reindex the array
    }

    public function updatedNumberUnits($value)
    {
        $unitCount = (int)$value;

        // Adjust the number of units in the array
        if ($unitCount > count($this->units)) {
            for ($i = count($this->units); $i < $unitCount; $i++) {
                $this->units[] = ['name' => ''];
            }
        } else {
            $this->units = array_slice($this->units, 0, $unitCount);
        }
    }

    public function removeTypeUnit($index)
    {
        if (isset($this->units[$index])) {
            unset($this->units[$index]);
            $this->units = array_values($this->units);
            $this->numberUnits = count($this->units); // Update the floors count
        }
    }
    
    // Save Property Units
    public function saveUnits($propertyId){

        foreach ($this->propertyUnits as $type) {
            // Create Unit Type
            $unitType = PropertyUnitType::create([
                'company_id' => current_company()->id,
                'property_id' => $propertyId,
                'name' => $type['unitName'],
                'description' => $type['unitDesc']?? null,
                'price' => $type['price'],
                'capacity' => $type['capacity'],
                'size' => $type['unitSize']?? null,
                // 'features' => json_encode($unit['unitFeatures']?? []),
            ]);

            // for($i = 0; $i < $unit['numberUnits']; $i++){
            foreach($type['units'] as $index => $unit){
                $propertyUnit = PropertyUnit::create([
                    'company_id' => current_company()->id,
                    'property_id' => $propertyId,
                    'property_unit_type_id' => $unitType->id,
                    'name' => $unit['name'],
                    'capacity' => $type['capacity'],
                ]);
                $propertyUnit->save();

                // Attach amenities to the property
                if(count($type['unitFeatures']) >= 1){
                    foreach($type['unitFeatures'] as $feature){
                        PropertyFeature::create([
                            'company_id' => current_company()->id,
                            'property_unit_type_id' => $unitType->id,
                            'feature_id' => $feature,
                        ]);
                    }
                }
            }
        }
    }

    public function updatedFloors($value)
    {
        $floorCount = (int)$value;

        // Adjust the number of floors in the array
        if ($floorCount > count($this->propertyFloors)) {
            for ($i = count($this->propertyFloors); $i < $floorCount; $i++) {
                $this->propertyFloors[] = ['name' => '', 'description' => ''];
            }
        } else {
            $this->propertyFloors = array_slice($this->propertyFloors, 0, $floorCount);
        }
    }

    public function removeFloor($index)
    {
        if (isset($this->propertyFloors[$index])) {
            unset($this->propertyFloors[$index]);
            $this->propertyFloors = array_values($this->propertyFloors);
            $this->floors = count($this->propertyFloors); // Update the floors count
        }
    }

    public function saveFloors($propertyId)
    {
        $this->validate([
            'propertyFloors.*.name' => 'required|string|max:255',
            'propertyFloors.*.description' => 'nullable|string|max:255',
        ]);

        foreach ($this->propertyFloors as $floor) {
            PropertyFloor::create([
                'company_id' => current_company()->id,
                'property_id' => $propertyId,
                'name' => $floor['name'],
                'description' => $floor['description'] ?? null,
            ]);
        }

        // Reset the component state
        $this->reset(['floors', 'propertyFloors']);
    }

    public function confirm(){
        // Validate input
        $this->validate([
            'name' => 'required|string',
        ]);

        // Create the property
        $property = Property::create([
            'company_id' => current_company()->id,
            'property_type_id' => $this->type->id,
            'name' => $this->name,
            'invoicing_type' => $this->invoicing,
            'country_id' => $this->country,
            'state_id' => $this->state,
            'city' => $this->city,
            'zip' => $this->zip,
            'address' => $this->street,
            'description' => $this->description,
            'status' => 'active',
        ]);

        // Attach floors to the property
        if(count($this->propertyFloors) >= 1){
            $this->saveFloors($property->id);
        }

        // Attach amenities to the property
        if(count($this->selectedAmenity) >= 1){
            foreach($this->selectedAmenity as $amenity){
                PropertyAmenity::create([
                    'company_id' => current_company()->id,
                    'property_id' => $property->id,
                    'amenity_id' => $amenity,
                ]);
            }
        }

        // Create Units & Unit Types
        if(count($this->propertyUnits) >= 1){
            $this->saveUnits($property->id);
        }

        // Flash success message
        session()->flash('success', __('Property has been saved successfully!'));

        // Reset form fields
        $this->reset();

        return $this->redirect(Route::subdomainRoute('properties.show', ['property' => $property->id]), navigate: true);
    }
}
