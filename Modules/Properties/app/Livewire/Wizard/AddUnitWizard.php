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

class AddUnitWizard extends SimpleWizard
{
    public $property, $selectedProperty, $unitProperty, $unitName, $unitFloor, $numberUnits = 1, $capacity = 1, $unitType, $unitSize = 0, $unitDesc, $unitPrice = 0, $prices = 1, $priceRate, $unitRate = 0;

    public array $properties = [], $countries = [], $selectedAmenity = [], $propertyUnits = [], $leaseTerms = [], $unitFeatures = [], $units = [], $unitTypes = [], $unitPrices = [];
    public $propertyFloors;

    public function mount(){
        $this->showButtons = false;

        $this->properties = toSelectOptions(Property::isCompany(current_company()->id)->get(), 'id', 'name');
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

    public function updatedSelectedProperty(){
        $property = Property::find($this->selectedProperty);
        $this->propertyFloors = $property->floors()->get();
    }

    public function steps(){
        return [
            Step::make(0, 'Define Your Units 🏢', false),
        ];
    }

    public function stepPages(){
        return [
            StepPage::make('Define Your Units 🏢', '', 0)->component('app::wizard.step-page.special.unit.add-units'),
        ];
    }


    // Add the unit to the propertyUnits array
    public function addUnit()
    {
        // Validate the form data before adding
        $this->validate([
            // 'unitProperty' => 'required|string',
            'unitName' => 'required|string',
            'unitDesc' => 'nullable|string',
            'numberUnits' => 'required|integer|min:1',
            'capacity' => 'required|integer|min:1',
            'unitSize' => 'nullable|numeric|min:1',
        ]);

        // Add the current unit data to the propertyUnits array
        $this->propertyUnits[] = [
            'unitProperty' => $this->selectedProperty,
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
        $this->reset(['selectedProperty', 'unitName', 'unitDesc', 'numberUnits', 'capacity', 'unitSize', 'unitFeatures', 'unitPrice']);
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
                'property_id' => $type['unitProperty'],
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
                            'property_id' => $type['unitProperty'],
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
                            'property_id' => $type['unitProperty'],
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

    public function increaseCapacity(){
        $this->capacity++;
    }

    public function decreaseCapacity(){
        if($this->capacity >= 1){
            $this->capacity--;
        }
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
                'name' => $unit->unitType->name.' '. lease_term($price['rate_type'])->name,
                'price' => $price['rate'] ?? 0,
                'is_default' => $price['default'] ?? false,
            ]);
        }

        // Reset the component state
        $this->reset(['prices', 'unitPrices']);


    }

    public function submitUnits(){
        // $this->validate([
        //     'selectedProperty' => 'required|integer|exists:properties,id',
        // ]);

        // Create Units & Unit Types
        if(count($this->propertyUnits) >= 1){
            $this->saveUnits($this->selectedProperty);
        }

        // Flash success message
        session()->flash('success', __('Units has been saved successfully!'));

        // Reset form fields
        $this->reset();

        return $this->redirect(route('properties.units.lists'), navigate: true);

    }
}
