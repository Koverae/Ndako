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
use Modules\Properties\Models\Property\LeaseTerm;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyFeature;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyUnitType;
use Modules\Properties\Models\Property\PropertyUnitTypePricing;
use Modules\Properties\Models\Property\Utility;

class UnitTypeForm extends LightWeightForm
{
    public $type;
    public $name, $pricing, $property, $unitPrice = null, $description, $capacity, $size;
    public array $includedFeatures = [], $includedUtilities = [], $propertyOptions = [], $pricingOptions = [], $utilitiesOptions = [], $unitTypeUtilities = [];

    public function mount($type = null){
        if($type){
            $this->type = $type;
            $this->name = $type->name;
            $this->description = $type->description;
            $this->capacity = $type->capacity;
            $this->size = $type->size;
            $this->pricing = $type->pricing_id;
            $this->property = $type->property_id;

            $this->unitPrice = $type->price;
            $this->includedFeatures = $type->features->pluck('id')->toArray();
            $utilities = [
                ['id' => 'water', 'label' => 'Water'],
                ['id' => 'wifi', 'label' => 'WiFi'],
            ];
            $this->includedFeatures = toSelectOptions(PropertyFeature::isCompany(current_company())->get(), 'id', 'name');
        }

        $this->propertyOptions = toSelectOptions(Property::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->pricingOptions = toSelectOptions(PropertyUnitTypePricing::isCompany(current_company()->id)->get(), 'id', 'name');
        $this->utilitiesOptions = toSelectOptions(Utility::isCompany(current_company()->id)->get(), 'id', 'name');
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
            Input::make('name', "Unit Type Name", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Room 102'))->component('app::form.input.ke-title'),
            Input::make('unit-property', 'Property', 'select', 'property', 'left', 'none', 'general', "", "", $this->propertyOptions),
            // Pricing & Availability
            Input::make('unit-price', "Base Pricing", 'select', 'pricing', 'left', 'none', 'pricing-availability', __(''), "", $this->pricingOptions),
            // Input::make('unit-discounted-price', "Discounted Price", 'text', 'type', 'left', 'none', 'pricing-availability', __(''))->component('app::form.input.unit-price'),
            // Input::make('unit-status', 'Availability Status', 'select', 'status', 'left', 'none', 'pricing-availability', "", "", $this->statusOptions),
            // Input::make('unit-rental-status', 'Rental/Booking Status', 'select', 'status', 'left', 'none', 'pricing-availability', "", "", $this->statusOptions),
            // Features & Utilities
            Input::make('unit-capacity', "Capacity", 'text', 'capacity', 'left', 'none', 'feature-utility', __('Number of people the unit can accommodate')),
            Input::make('unit-size', "Size", 'text', 'size', 'left', 'none', 'feature-utility', __('e.g. 500 sq. ft')),
            Input::make('unit-features', "Included Features", 'tag', 'size', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedFeatures, 'options' => $this->utilitiesOptions]),
            Input::make('unit-utilities', "Included Utilities", 'tag', 'size', 'left', 'none', 'feature-utility', "", "", ['data' => $this->includedFeatures, 'options' => $this->utilitiesOptions]),
            // Images & Notes
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'none', 'image-note', "", ""),
            // Input::make('unit-status', "Status", 'text', 'status', 'left', 'none', 'general', __('e.g. Airbnb')),
        ];
    }


}
