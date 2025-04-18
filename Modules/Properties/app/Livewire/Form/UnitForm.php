<?php

namespace Modules\Properties\Livewire\Form;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\SimpleAvatarForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Property\Feature;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Properties\Models\Property\PropertyUtility;
use Modules\Properties\Models\Property\Utility;

class UnitForm extends LightWeightForm
{
    public $unit;
    public $name, $description, $property, $type, $capacity = 1, $size, $status, $unitPrice = null, $floor, $identifier;
    public array $includedFeatures = [], $includedUtilities = [], $typeOptions = [], $statusOptions = [], $leaseTermOptions = [], $floorOptions = [], $propertyOptions = [], $utilitiesOptions = [], $featureOptions = [], $pricingOptions = [];

    // Define validation rules
    protected $rules = [
        'name' => 'required|string|max:30',
        'identifier' => 'nullable|string',
        'status' => 'nullable|string',
        'description' => 'nullable|string:200',
        'property' => 'nullable|integer|exists:properties,id',
        'floor' => 'nullable|integer|exists:property_floors,id',
        'type' => 'nullable|integer|exists:property_unit_types,id',
        // 'pricing' => 'nullable|integer|exists:property_unit_type_pricings,id',
        // 'selectedUtility' => 'required|exists:utilities,id|unique:property_utilities,id',
        // 'selectedFeature' => 'required|exists:features,id|unique:property_features,id'
    ];

    public function mount($unit = null){
        if($unit){
            $this->unit = $unit;
            $this->name = $unit->name;
            $this->description = $unit->description;
            $this->capacity = $unit->capacity;
            $this->size = $unit->unitType->size;
            $this->status = $unit->status;
            $this->floor = $unit->floor_id;
            $this->identifier = $unit->identifier;
            $this->type = $unit->property_unit_type_id;
            $this->property = $unit->property_id;

            $this->unitPrice = PropertyUnitType::find($this->type)->price;
            $includedFeatures = PropertyFeature::isCompany(current_company()->id)->isUnitType($this->type)->get();

            // Map the data into an array of ['id' => id, 'name' => name]
            $included = $includedFeatures->map(fn ($item) => [
                'id' => $item->id,                    // The property feature id
                'name' => $item->feature->name        // The feature name
            ]);

            // Convert it into an array and pass it to toSelectOptions
            $this->includedFeatures = toSelectOptions(
                $included->toArray(),  // Convert the collection to a regular array
                'id',                  // The key for the select options
                'name'                 // The name to display in the options
            );

            $includedUtilities = PropertyUtility::isCompany(current_company()->id)->isUnitType($this->type)->get();

            // Map the data into an array of ['id' => id, 'name' => name]
            $includedU = $includedUtilities->map(fn ($item) => [
                'id' => $item->id,                    // The property feature id
                'name' => $item->utility->name        // The feature name
            ]);

            // Convert it into an array and pass it to toSelectOptions
            $this->includedUtilities = toSelectOptions(
                $includedU->toArray(),  // Convert the collection to a regular array
                'id',                  // The key for the select options
                'name'                 // The name to display in the options
            );
        }
        $this->typeOptions = toSelectOptions(PropertyUnitType::isCompany(current_company()->id)->isProperty($this->unit->property->id)->get(), 'id', 'name');
        $this->leaseTermOptions = toSelectOptions(LeaseTerm::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->pricingOptions = toSelectOptions(PropertyUnitTypePricing::isCompany(current_company()->id)->isProperty($this->unit->property->id)->isPropertyUnit($this->unit->unitType->id)->get(), 'id', 'name');
        $this->floorOptions = toSelectOptions(PropertyFloor::isCompany(current_company()->id)->isProperty($this->unit->property->id)->get(), 'id', 'name');
        $this->propertyOptions = toSelectOptions(Property::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->featureOptions = toSelectOptions(Feature::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->utilitiesOptions = toSelectOptions(Utility::isCompany(current_company()->id)->get(), 'id', 'name');

        $status = [
            ['id' => 'vacant', 'label' => 'Vacant (V)'],
            ['id' => 'reserved', 'label' => 'Reserved (R)'],
            ['id' => 'occupied', 'label' => 'Occupied (O)'],
            ['id' => 'occupied-clean', 'label' => 'Occupied Clean (OC)'],
            ['id' => 'occupied-dirty', 'label' => 'Occupied Dirty (OD)'],
            ['id' => 'vacant-clean', 'label' => 'Vacant Clean (VC)'],
            ['id' => 'vacant-dirty', 'label' => 'Vacant Dirty (VD)'],
            ['id' => 'compliment', 'label' => 'Compliment (C)'],
            ['id' => 'not-disturb', 'label' => 'Do Not Disturb (DND)'],
            ['id' => 'out-service', 'label' => 'Out Of Service (OS)'],
            ['id' => 'due-out', 'label' => 'Due Out / Expected departure (DO/ED)'],
            ['id' => 'expected-arrival', 'label' => 'Expected Arrival (EA)'],
            ['id' => 'check-out', 'label' => 'Check Out (CO)'],
            ['id' => 'late-check-out', 'label' => 'LAte Check Out (LCO)'],
        ];
        $this->statusOptions = toSelectOptions($status, 'id', 'label');
    }

    public function updatedType($value){
        $this->type = $value;

        $this->unitPrice = PropertyUnitType::find($this->type)->price;

        $includedFeatures = PropertyFeature::isCompany(current_company()->id)->isUnitType($this->type)->get();

        // Map the data into an array of ['id' => id, 'name' => name]
        $included = $includedFeatures->map(fn ($item) => [
            'id' => $item->id,                    // The property feature id
            'name' => $item->feature->name        // The feature name
        ]);

        // Convert it into an array and pass it to toSelectOptions
        $this->includedFeatures = toSelectOptions(
            $included->toArray(),  // Convert the collection to a regular array
            'id',                  // The key for the select options
            'name'                 // The name to display in the options
        );

        $includedUtilities = PropertyUtility::isCompany(current_company()->id)->isUnitType($this->type)->get();

        // Map the data into an array of ['id' => id, 'name' => name]
        $includedU = $includedUtilities->map(fn ($item) => [
            'id' => $item->id,                    // The property feature id
            'name' => $item->utility->name        // The feature name
        ]);

        // Convert it into an array and pass it to toSelectOptions
        $this->includedUtilities = toSelectOptions(
            $includedU->toArray(),  // Convert the collection to a regular array
            'id',                  // The key for the select options
            'name'                 // The name to display in the options
        );
    }

    public function capsules() : array
    {
        return [
            Capsule::make('property-type', __('Property Unit Type'), __('Unit type linked to this unit.'), 'link', 'fa fa-home-user', route('properties.unit-types.show', ['type' => $this->type]) ),
            Capsule::make('tenant', __('Tenant / Guest'), __('Guest or Tenant occuping the unit'), 'modal', 'fa fa-user'),
            Capsule::make('reservations', __('Reservations'), __('Reservations made for this unit'), 'link', 'fa fa-tasks', route('bookings.lists', ['unit' => $this->unit->id])),
            Capsule::make('maintenance-request', __('Maintenance Requests'), __('Maintenance requests made for this unit.'), 'link', 'fa fa-tools', route('tasks.lists', ['unit' => $this->unit->id])),
        ];
    }


    public function groups() : array
    {
        return [
            Group::make('general',__("Basic Details"), ""),
            Group::make('pricing-availability',__("Pricing & Availability"), ""),
            Group::make('feature-utility',__("Features & Utilities"), ""),
            // Group::make('image-note',__("Images & Notes"), "")->component('app::form.tab.group.gallery-photo'),
        ];
    }


    public function inputs(): array
    {
        return [
            Input::make('name', "Unit Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Room 102'))->component('app::form.input.ke-title'),
            Input::make('unit-property', 'Unit Property', 'select', 'property', 'left', 'none', 'general', "", "", $this->propertyOptions),
            Input::make('unit-type', 'Unit Type', 'select', 'type', 'left', 'none', 'general', "", "", $this->typeOptions),
            Input::make('unit-floor', 'Floor/Section', 'select', 'floor', 'left', 'none', 'general', "", "", $this->floorOptions),
            Input::make('unit-identifier', "Unit Identifier", 'text', 'identifier', 'left', 'none', 'general'),
            // Pricing & Availability
            Input::make('unit-price', "Pricing", 'select', 'type', 'left', 'none', 'pricing-availability', __(''), "", $this->pricingOptions),
            // Input::make('unit-discounted-price', "Discounted Price", 'text', 'type', 'left', 'none', 'pricing-availability', __(''))->component('app::form.input.unit-price'),
            Input::make('unit-status', 'Availability Status', 'select', 'status', 'left', 'none', 'pricing-availability', "", "", $this->statusOptions),
            Input::make('unit-rental-status', 'Rental/Booking Status', 'select', 'status', 'left', 'none', 'pricing-availability', "", "", $this->statusOptions),
            // Features & Utilities
            Input::make('unit-capacity', "Capacity", 'text', 'capacity', 'left', 'none', 'feature-utility', __('Number of people the unit can accommodate')),
            Input::make('unit-size', "Size", 'text', 'size', 'left', 'none', 'feature-utility', __('e.g. 500 sq. ft')),
            Input::make('unit-features', "Included Features", 'tag', 'selectedFeature', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedFeatures, 'options' => $this->featureOptions, 'action' => 'addFeature', 'delete' => 'removeFeature']),
            Input::make('unit-utilities', "Included Utilities", 'tag', 'selectedUtility', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedUtilities, 'options' => $this->utilitiesOptions, 'action' => 'addUtility', 'delete' => 'removeUtility']),
            // Images & Notes
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'none', 'image-note', "", ""),
            // Input::make('unit-status', "Status", 'text', 'status', 'left', 'none', 'general', __('e.g. Airbnb')),
        ];
    }

    #[On('update-unit')]
    public function updateUnit(){
        $this->validate();
        $unit = PropertyUnit::find($this->unit->id);

        $unit->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'floor_id' => $this->floor,
            // 'identifier' => $this->identifier,
            'property_unit_type_id' => $this->type,
            'property_id' => $this->property,
        ]);
        $unit->save();
        return $this->redirect(route('properties.units.show', ['unit' => $unit->id]), navigate: true);
    }

    #[On('create-unit')]
    public function createUnit(){
        $this->validate();

        $unit = PropertyUnit::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'floor_id' => $this->floor,
            // 'identifier' => $this->identifier,
            'property_unit_type_id' => $this->type,
            'property_id' => $this->property,
        ]);
        $unit->save();
        return $this->redirect(route('properties.units.show', ['unit' => $unit->id]), navigate: true);
    }
}
