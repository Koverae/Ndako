<?php

namespace Modules\Properties\Livewire\Wizard;

use Livewire\Attributes\On;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Wizard\SimpleWizard;
use Modules\App\Livewire\Components\Wizard\Step;
use Modules\App\Livewire\Components\Wizard\StepPage;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyAmenity;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Settings\Models\Localization\Country;

class AddPropertyWizard extends SimpleWizard
{
    // Property
    public $type, $invoicing = 'rate', $name, $country, $street, $city, $state, $zip, $description, $floors = 0, $companyEmail, $companyPhone, $companyStreet, $companyCity, $companyState, $companyZip, $companyCountry;

    public $unitName, $unitFloor, $numberUnits = 1, $capacity = 1, $unitType, $unitSize = 0, $unitDesc, $unitPrice = 0, $prices = 1, $priceRate, $unitRate = 0;

    public array $propertyTypes = [], $countries = [], $selectedAmenity = [], $propertyFloors = [], $propertyUnits = [], $leaseTerms = [], $unitFeatures = [], $units = [], $unitTypes = [], $unitPrices = [];


    public function mount(){
        $this->showButtons = false;
        $this->propertyTypes = toSelectOptions(PropertyType::isCompany(current_company()->id)->where('property_type_group', '!=', 'commercial')->get(), 'id', 'name');
        $this->countries = toSelectOptions(Country::all(), 'id', 'common_name');

        $unitTypes = [
            // Basic & Standard Rooms
            ['id' => 'single-room', 'label' => 'Single Room 🛏️'],
            ['id' => 'double-room', 'label' => 'Double Room 🛏️🛏️'],
            ['id' => 'twin-room', 'label' => 'Twin Room 🛏️🛏️'],
            ['id' => 'triple-room', 'label' => 'Triple Room 🛏️🛏️🛏️'],
            ['id' => 'quadruple-room', 'label' => 'Quadruple Room 🛏️🛏️🛏️🛏️'],
            ['id' => 'family-room', 'label' => 'Family Room 👨‍👩‍👧‍👦'],
            ['id' => 'bunk-room', 'label' => 'Bunk Room 🛏️🛏️'],

            // Premium & Luxury Rooms
            ['id' => 'standard-room', 'label' => 'Standard Room 🌟'],
            ['id' => 'deluxe-room', 'label' => 'Deluxe Room 🌟'],
            ['id' => 'superior-room', 'label' => 'Superior Room ✨'],
            ['id' => 'executive-room', 'label' => 'Executive Room 💼'],
            ['id' => 'junior-suite', 'label' => 'Junior Suite 🏡'],
            ['id' => 'suite', 'label' => 'Suite 🏢'],
            ['id' => 'presidential-suite', 'label' => 'Presidential Suite 🏆'],
            ['id' => 'penthouse', 'label' => 'Penthouse 🌆'],

            // Specialty & Themed Rooms
            ['id' => 'honeymoon-suite', 'label' => 'Honeymoon Suite 💕'],
            ['id' => 'wellness-room', 'label' => 'Wellness Room 🧘'],
            ['id' => 'accessible-room', 'label' => 'Accessible Room ♿'],
            ['id' => 'tatami-room', 'label' => 'Tatami Room 🎎'],
            ['id' => 'themed-room', 'label' => 'Themed Room 🎭'],
            ['id' => 'smart-room', 'label' => 'Smart Room 🤖'],

            // Apartment & Long-Stay Options
            ['id' => 'studio-room', 'label' => 'Studio Room 🏢'],
            ['id' => 'serviced-apartment', 'label' => 'Serviced Apartment 🏡'],
            ['id' => 'loft-room', 'label' => 'Loft Room 🏙️'],
            ['id' => 'duplex-room', 'label' => 'Duplex Room 🏠'],

            // Budget & Shared Accommodation
            ['id' => 'shared-dormitory', 'label' => 'Shared Dormitory 🏘️'],
            ['id' => 'capsule-room', 'label' => 'Capsule Room 📦'],
            ['id' => 'micro-room', 'label' => 'Micro Room 🚪'],

            // Efficiency Apartments
            ['id' => 'efficiency-apartment', 'label' => 'Efficiency Apartment 🔄'],

            // Multi-Room Apartments
            ['id' => 'one-bedroom-apartment', 'label' => 'One-Bedroom Apartment 🛏️'],
            ['id' => 'two-bedroom-apartment', 'label' => 'Two-Bedroom Apartment 🏡'],
            ['id' => 'three-bedroom-apartment', 'label' => 'Three-Bedroom Apartment 🏠'],
            ['id' => 'penthouse-apartment', 'label' => 'Penthouse Apartment 🌆'],
            ['id' => 'garden-apartment', 'label' => 'Garden Apartment 🌿'],
            ['id' => 'basement-apartment', 'label' => 'Basement Apartment ⬇️'],

            // Townhouses & Multi-Story Living
            ['id' => 'duplex', 'label' => 'Duplex 🏠🏠'],
            ['id' => 'triplex', 'label' => 'Triplex 🏡🏡🏡'],
            ['id' => 'townhouse', 'label' => 'Townhouse 🏘️'],

            // Luxury & High-End Apartments
            ['id' => 'loft-apartment', 'label' => 'Loft Apartment 🏙️'],
            ['id' => 'serviced-apartment', 'label' => 'Serviced Apartment 🏢'],
            ['id' => 'corporate-apartment', 'label' => 'Corporate Apartment 💼'],
            ['id' => 'luxury-apartment', 'label' => 'Luxury Apartment 🌟'],
            ['id' => 'smart-apartment', 'label' => 'Smart Apartment 🤖'],
        ];
        $this->unitTypes = toSelectOptions($unitTypes, 'id', 'label');

        $this->leaseTerms = toSelectOptions(LeaseTerm::isCompany(current_company()->id)->get(), 'id', 'name');
    }

    public function steps(){
        return [
            Step::make(0, 'Add First Property 🏡', false),
            Step::make(1, 'Define Your Units 🏢', false),
        ];
    }

    public function stepPages(){
        return [
            StepPage::make('Add First Property 🏡', '', 0)->component('app::wizard.step-page.special.property.add-property'),
            StepPage::make('Define Your Units 🏢', '', 1)->component('app::wizard.step-page.special.property.add-units'),
        ];
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
            'unitFloor' => $this->unitFloor,
            'unitDesc' => $this->unitDesc,
            'numberUnits' => $this->numberUnits,
            'price' => $this->unitPrice,
            'capacity' => $this->capacity,
            'unitSize' => $this->unitSize,
            'unitPrices' => $this->unitPrices,
            'unitFeatures' => $this->unitFeatures,
            'units' => $this->units,
        ];

        // Optionally, reset the form fields for the next entry
        $this->unitFeatures = [];
        $this->unitPrices = [];
        $this->prices = 1;
        $this->units = [];
        $this->capacity = 1;
        $this->numberUnits = 1;
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
                $this->units[] = ['name' => '', 'floor' => ''];
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

            if(count($type['unitPrices']) >= 1) {
                foreach ($type['unitPrices'] as $price) {
                    PropertyUnitTypePricing::updateOrCreate(
                        [
                            'company_id' => current_company()->id,
                            'property_id' => $propertyId,
                            'property_unit_type_id' => $unitType->id,
                            'lease_term_id' => $price['rate_type'],
                            'name' => $unitType->name . ' ' . lease_term($price['rate_type'])->name,
                        ],
                        [
                            'price' => $price['rate'] ?? 0,
                            'is_default' => $price['default'] ?? false,
                        ]
                    );
                }

                // Reset the component state
                $this->reset(['prices', 'unitPrices']);
            }

            // for($i = 0; $i < $unit['numberUnits']; $i++){
            foreach($type['units'] as $index => $unit) {
                    $floor = PropertyFloor::isCompany(current_company()->id)
                        ->where('name', $unit['floor'])
                        ->first() ?? null;

                    PropertyUnit::updateOrCreate(
                        [
                            'company_id' => current_company()->id,
                            'property_id' => $propertyId,
                            'floor_id' => $floor->id ?? null,
                            'property_unit_type_id' => $unitType->id,
                            'name' => $unit['name'],
                        ],
                        [
                            'capacity' => $type['capacity'],
                        ]
                    );

                    // Attach amenities to the property (éviter la duplication ici aussi)
                    if(count($type['unitFeatures']) >= 1) {
                        foreach($type['unitFeatures'] as $feature) {
                            PropertyFeature::firstOrCreate([
                                'company_id' => current_company()->id,
                                'property_unit_type_id' => $unitType->id,
                                'feature_id' => $feature,
                            ]);
                        }
                    }
            }

        }

        $this->propertyUnits = [];
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

    public function addPricing(){
        $this->prices++;
        // $priceCount = $this->prices++;

        // Adjust the number of floors in the array
        if ($this->prices > count($this->unitPrices)) {
            for ($i = count($this->unitPrices); $i < $this->prices; $i++) {
                $this->unitPrices[] = ['rate_type' => '', 'rate' => '', 'default' => false];
            }
        } else {
            $this->unitPrices = array_slice($this->unitPrices, 0, $this->prices);
        }

    }

    public function removePricing($index){
        if(isset($this->unitPrices[$index]) && count($this->unitPrices) > 1){
            unset($this->unitPrices[$index]);
            $this->unitPrices = array_values($this->unitPrices);
            $this->prices = count($this->unitPrices);
        }
    }

    public function savePricing($unit){
        // $this->validate([
        //     'unitPrices.*.rate_type' => 'required|integer',
        //     'unitPrices.*.rate' => 'required|integer',
        //     'unitPrices.*.default' => 'nullable',
        // ]);

        foreach ($this->unitPrices as $price) {
            PropertyUnitTypePricing::create([
                'company_id' => current_company()->id,
                'property_id' => $unit->property->id,
                'property_unit_type_id' => $unit->unitType->id,
                'lease_term_id' => $price['rate_type'],
                'name' => $unit->unitType->name.' '. lease_term($price['rate_type']),
                'price' => $price['rate'] ?? 0,
                'is_default' => $price['default'] ?? false,
            ]);
        }

        // Reset the component state
        $this->reset(['prices', 'unitPrices']);


    }

    public function addProperty(){
        $this->validate([
            'name' => 'required|string',
        ]);
        $this->goToNextStep();
    }

    public function submitProperty(){
        $this->validate([
            'name' => 'required|string|max:100',
        ]);
        // Create the property
        $property = Property::create([
            'company_id' => current_company()->id,
            'property_type_id' => $this->type,
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

        $this->selectedAmenity = [];
        $this->reset(['name', 'type', 'description', 'country', 'invoicing', 'state', 'city', 'street', 'selectedAmenity']);


        // Flash success message
        session()->flash('success', __('Property has been saved successfully!'));

        // Reset form fields
        $this->reset();

        return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);


    }

    public function increaseCapacity(){
        $this->capacity++;
    }

    public function decreaseCapacity(){
        if($this->capacity >= 1){
            $this->capacity--;
        }
    }


    // public function confirm(){
    //     // Flash success message
    //     session()->flash('success', __('Property has been saved successfully!'));

    //     // Reset form fields
    //     $this->reset();

    //     return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);
    // }

}
