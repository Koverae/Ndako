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
use Modules\Properties\Models\Property\PropertyType;

class PropertyTypeForm extends LightWeightForm
{
    public $type;
    public $name, $category, $unit_structure, $description;
    public array $categoryOptions = [], $structureOptions = [];

    protected $rules = [
        'name' => 'required|string|max:50',
    ];

    public function mount($type = null){
        if($type){
            $this->type = $type;
            $this->name = $type->name;
            $this->description = $type->description;
            $this->category = $type->property_type_group;
            $this->unit_structure = $type->property_type;
        }

        $categories = [
            ['id' => 'residential', 'label' => 'Residential'],
            ['id' => 'commercial', 'label' => 'Commercial'],
            ['id' => 'hospitality', 'label' => 'Hospitality'],
            ['id' => 'mixed', 'label' => 'Mixed'],
        ];
        $this->categoryOptions = toSelectOptions($categories, 'id', 'label');

        $structures = [
            ['id' => 'single', 'label' => 'Single Unit'],
            ['id' => 'multi', 'label' => 'Multi Unit'],
            ['id' => 'custom', 'label' => 'Custom Configured'],
        ];
        $this->structureOptions = toSelectOptions($structures, 'id', 'label');
    }

    public function capsules() : array
    {
        return [
            Capsule::make('properties', __('Property'), __('Properties linked to this property type.'), 'link', 'fa fa-home-user', route('properties.lists', ['type' => $this->type ?? null]), ['parent' => $this->type, 'amount' => $this->type ? $this->type->properties->count() : 0])->component('app::form.capsule.depends'),
        ];
    }

    public function groups() : array
    {
        return [
            Group::make('general',__("Basic Informations"), ""),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Property Type", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Apartment'))->component('app::form.input.ke-title'),
            Input::make('category', 'Category', 'select', 'category', 'left', 'none', 'general', "", "", $this->categoryOptions),
            Input::make('unit-structure', 'Unit Structure', 'select', 'unit_structure', 'left', 'none', 'general', "", "Defines whether a property type is single-unit, multi-unit, or custom-configured", $this->structureOptions),
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'none', 'general', "", "")
        ];
    }

    #[On('create-property-type')]
    public function createPropertyType()
    {
        $this->validate();
        $type = PropertyType::create([
            'company_id' => current_company()->id,
            'name' => $this->name,
            'description' => $this->description,
            'property_type_group' => $this->category,
            'property_type' => $this->unit_structure,
        ]);
        $type->save();

        return $this->redirect(route('properties.types.show', ['type' => $type->id]), navigate: true);
    }

    #[On('update-property-type')]
    public function updatePropertyType()
    {
        $this->validate();
        $type = PropertyType::find($this->type->id);
        $type->update([
            'name' => $this->name,
            'description' => $this->description,
            'property_type_group' => $this->category,
            'property_type' => $this->unit_structure,
        ]);
        $type->save();
        return $this->redirect(route('properties.types.show', ['type' => $type->id]), navigate: true);
    }

    public function updated(){
        $this->dispatch('change');
    }
    
}
