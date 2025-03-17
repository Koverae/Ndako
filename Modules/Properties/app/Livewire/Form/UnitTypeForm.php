<?php

namespace Modules\Properties\Livewire\Form;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Template\SimpleAvatarForm;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Traits\Files\HasFileUploads;
use Modules\Properties\Models\Property\Feature;
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Properties\Models\Property\PropertyUtility;
use Modules\Properties\Models\Property\Utility;

class UnitTypeForm extends LightWeightForm
{
    use HasFileUploads;

    public $type;
    public $name, $pricing, $property, $unitPrice = null, $description, $capacity, $size;
    public $selectedItem, $selectedFeature, $selectedUtility;
    public array $includedFeatures = [], $includedUtilities = [], $propertyOptions = [], $pricingOptions = [], $utilitiesOptions = [], $featureOptions = [];


    // Define validation rules
    protected $rules = [
        'name' => 'required|string|max:50',
        'capacity' => 'nullable|integer',
        'size' => 'nullable|integer',
        'description' => 'nullable|string:200',
        'property' => 'nullable|integer|exists:properties,id',
        'pricing' => 'nullable|integer|exists:property_unit_type_pricings,id',
        // 'selectedUtility' => 'required|exists:utilities,id|unique:property_utilities,id',
        // 'selectedFeature' => 'required|exists:features,id|unique:property_features,id'
    ];

    public function mount($type = null){
        if($type){
            $this->inputId = 'photo-' . uniqid(); // Prevent conflicts
            $this->model = $type;
            $this->path = 'property_images';

            $this->type = $type;
            $this->name = $type->name;
            $this->description = $type->description;
            $this->capacity = $type->capacity;
            $this->size = $type->size;
            $this->pricing = $type->prices()->isDefault()->first()->id;
            $this->property = $type->property_id;

            $this->unitPrice = $type->price;
            $includedFeatures = PropertyFeature::isCompany(current_company()->id)->isUnitType($type->id)->get();

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

            $includedUtilities = PropertyUtility::isCompany(current_company()->id)->isUnitType($type->id)->get();

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

        $this->propertyOptions = toSelectOptions(Property::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->pricingOptions = toSelectOptions(PropertyUnitTypePricing::isCompany(current_company()->id)->isProperty($this->type->property_id)->isPropertyUnit($this->type->id)->get(), 'id', 'name');
        $this->featureOptions = toSelectOptions(Feature::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->utilitiesOptions = toSelectOptions(Utility::isCompany(current_company()->id)->get(), 'id', 'name');


        $this->existingImages = $this->type->images ?? [];
    }

    public function capsules() : array
    {
        return [
            Capsule::make('property-type', __('Property'), __('Property linked to this unit type.'), 'link', 'fa fa-home-user', route('properties.show', ['property' => $this->property ?? 3]), ['parent' => $this->property, 'amount' => ""])->component('app::form.capsule.depends'),
            Capsule::make('units', __('Units'), __('Units linked to this unit type.'), 'link', 'fa fa-home', route('properties.units.lists', ['type' => $this->type]), ['parent' => $this->type, 'amount' => ""])->component('app::form.capsule.depends'),
            Capsule::make('prices', __('Pricing'), __('Pricing of this unit type.'), 'modal', 'fa fa-tags', "{component: 'properties::modal.pricing-modal', arguments: { unitType: {$this->type->id} }}"),
        ];
    }

    public function groups() : array
    {
        return [
            Group::make('general',__("Basic Informations"), ""),
            Group::make('pricing-availability',__("Pricing & Availability"), ""),
            Group::make('feature-utility',__("Features & Utilities"), ""),
            Group::make('image-note',__("Images & Notes"), "")->component('app::form.tab.group.gallery-photo'),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Unit Type Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Twin Room'))->component('app::form.input.ke-title'),
            Input::make('unit-property', 'Property', 'select', 'property', 'left', 'none', 'general', "", "", $this->propertyOptions),
            // Pricing & Availability
            Input::make('unit-price', "Base Pricing", 'select', 'pricing', 'left', 'none', 'pricing-availability', __(''), "", $this->pricingOptions),
            // Features & Utilities
            Input::make('unit-capacity', "Capacity", 'tel', 'capacity', 'left', 'none', 'feature-utility', __('Number of people the unit can accommodate')),
            Input::make('unit-size', "Size", 'tel', 'size', 'left', 'none', 'feature-utility', __('e.g. 500 sq. ft')),
            Input::make('unit-features', "Included Features", 'tag', 'selectedFeature', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedFeatures, 'options' => $this->featureOptions, 'action' => 'addFeature', 'delete' => 'removeFeature']),
            Input::make('unit-utilities', "Included Utilities", 'tag', 'selectedUtility', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedUtilities, 'options' => $this->utilitiesOptions, 'action' => 'addUtility', 'delete' => 'removeUtility']),
            // Images & Notes
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'none', 'image-note', "", "")
        ];
    }

    public function addFeature()
    {
        // Validate that the selected feature exists and is unique for this unit type.
        $this->validate();

        if ($this->type && $this->selectedFeature && !$this->type->features->contains('id', $this->selectedFeature)) {
            $existingFeature = PropertyFeature::isCompany(current_company()->id)->isUnitType($this->type->id)->isFeature($this->selectedFeature)->exists();

            if ($existingFeature) {
                // You can add a message or handle the case where the feature is already selected.
                session()->flash('error', 'This feature is already selected for the unit type.');
                return;
            }

            PropertyFeature::create([
                'property_unit_type_id' => $this->type->id,
                'feature_id' => $this->selectedFeature,
                'company_id' => current_company()->id,
            ]);
            // $this->loadFeatures();
            $this->selectedFeature = null; // Reset the dropdown.
            $this->mount($this->type);
        }
    }

    public function addUtility()
    {
        // Validate that the selected feature exists and is unique for this unit type.
        $this->validate();

        if ($this->type && $this->selectedUtility && !$this->type->utilities->contains('id', $this->selectedUtility)) {
            $existingFeature = PropertyUtility::isCompany(current_company()->id)->isUnitType($this->type->id)->isUtility($this->selectedUtility)->exists();

            if ($existingFeature) {
                // You can add a message or handle the case where the feature is already selected.
                session()->flash('error', 'This Utility is already selected for the unit type.');
                return;
            }

            PropertyUtility::create([
                'property_unit_type_id' => $this->type->id,
                'utility_id' => $this->selectedUtility,
                'company_id' => current_company()->id,
            ]);
            // $this->loadFeatures();
            $this->selectedUtility = null; // Reset the dropdown.
            $this->mount($this->type);
        }
    }

    public function removeFeature($itemId)
    {
        // Find the feature by its ID.
        $feature = PropertyFeature::where('id', $itemId)
            ->isUnitType($this->type->id)
            ->first();

        if ($feature) {
            $feature->delete(); // Delete the feature from the database.
            $this->mount($this->type);
        }
    }

    public function removeUtility($itemId)
    {
        // Find the feature by its ID.
        $utility = PropertyUtility::where('id', $itemId)
            ->isUnitType($this->type->id)
            ->first();

        if ($utility) {
            $utility->delete(); // Delete the feature from the database.
            $this->mount($this->type);
        }
    }

    #[On('create-unit-type')]
    public function createUnitType()
    {
        $this->validate();
        $type = PropertyUnitType::create([
            'company_id' => current_company()->id,
            'name' => $this->name,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'size' => $this->size,
            'pricing_id' => $this->pricing,
            'property_id' => $this->property,
        ]);
        $type->save();

        return $this->redirect(route('properties.unit-types.show', ['type' => $type->id]), navigate: true);
    }

    public function updatedPricing(){
        PropertyUnitTypePricing::isPropertyUnit($this->type->id)->isDefault()->update(['is_default' => false]);
        $pricing = PropertyUnitTypePricing::find($this->pricing);
        $pricing->update(['is_default' => true]);
    }

    #[On('update-unit-type')]
    public function updateUnitType()
    {
        $this->validate();
        $type = PropertyUnitType::find($this->type->id);
        $type->update([
            'name' => $this->name,
            'description' => $this->description,
            'capacity' => $this->capacity,
            'size' => $this->size,
            'pricing_id' => $this->pricing,
            'property_id' => $this->property,
        ]);
        $type->save();
        return $this->redirect(route('properties.unit-types.show', ['type' => $type->id]), navigate: true);
    }

    public function updated(){
        $this->dispatch('change');
    }

    #[On('updated-pricing')]
    public function updatedThePrices(){
        $this->pricingOptions = toSelectOptions(PropertyUnitTypePricing::isCompany(current_company()->id)->isProperty($this->type->property_id)->isPropertyUnit($this->type->id)->get(), 'id', 'name');
    }

    // Images Upload


}
