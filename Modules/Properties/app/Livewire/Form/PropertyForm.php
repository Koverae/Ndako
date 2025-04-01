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
use Modules\App\Traits\Files\HasFileUploads;
use Modules\Properties\Models\Property\Amenity;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyAmenity;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnit;

class PropertyForm extends LightWeightForm
{
    use HasFileUploads;

    public $property;
    public $property_type, $invoicing = '', $name, $country, $street, $city, $state, $zip, $description, $selectedAmenity;
    public $base_price = 25000, $payment_interval = 'monthly', $stay = 1;
    public array  $includedAmenities = [], $typeOptions = [], $invoiceOptions = [], $paymentIntervalOptions = [], $propertyAmenitiesOptions = [], $amenityOptions, $rateFrequencyOptions = [];

    protected $rules = [
        'name' => 'required|string|max:30',
    ];
    public function mount($property = null){
        // $this->default_img = 'placeholder';
        if($property){

            $this->inputId = 'photo-' . uniqid(); // Prevent conflicts
            $this->model = $property;
            $this->path = 'property_images';

            $this->property = $property;
            $this->name = $property->name;
            $this->property_type = $property->property_type_id;
            // $this->invoicing = $property->invoicing_type; Invoicing Type (rental, rate)
            $this->country = $property->country_id;
            $this->state = $property->state_id;
            $this->city = $property->city;
            $this->zip = $property->zip;
            $this->street = $property->address;
            $this->description = $property->description;

            // $this->propertyAmenitiesOptions = toSelectOptions(PropertyAmenity::isProperty($property->id)->get(), 'id', 'id');
            $includedAmenities = PropertyAmenity::isCompany(current_company()->id)->isProperty($property->id)->get();

            // Map the data into an array of ['id' => id, 'name' => name]
            $included = $includedAmenities->map(fn ($item) => [
                'id' => $item->id,                    // The property feature id
                'name' => $item->amenity->name        // The feature name
            ]);

            // Convert it into an array and pass it to toSelectOptions
            $this->includedAmenities = toSelectOptions(
                $included->toArray(),  // Convert the collection to a regular array
                'id',                  // The key for the select options
                'name'                 // The name to display in the options
            );
        }

        $this->typeOptions = toSelectOptions(PropertyType::isCompany(current_company()->id)->where('property_type_group', '!=', 'commercial')->get(), 'id', 'name');
        $this->amenityOptions = toSelectOptions(Amenity::isCompany(current_company()->id)->get(), 'id', 'name');

        $invoiceTypes = [
            ['id' => 'rental', 'label' => 'Rental Invoice'],
            ['id' => 'rate', 'label' => 'Rate Based Invoice'],
        ];
        $this->invoiceOptions = toSelectOptions($invoiceTypes, 'id', 'label');

        $payments = [
            ['id' => 'monthly', 'label' => 'Monthly'],
            ['id' => 'quarterly', 'label' => 'Quarterly'],
            ['id' => 'annually', 'label' => 'Annually'],
        ];
        $this->paymentIntervalOptions = toSelectOptions($payments, 'id', 'label');

        $frenquency = [
            ['id' => 'daily', 'label' => 'Daily'],
            ['id' => 'weekly', 'label' => 'Weekly'],
        ];
        $this->rateFrequencyOptions = toSelectOptions($frenquency, 'id', 'label');

        $propertyAmenities = [
            ['id' => 'swimming-pool', 'label' => 'Swimming Pool'],
            ['id' => 'gym', 'label' => 'Gym'],
            ['id' => 'parking', 'label' => 'Parking'],
            ['id' => 'parkin', 'label' => 'Parking'],
            ['id' => 'parkinrg', 'label' => 'Parking'],
        ];

    }

    public function tabs() : array
    {
        return [
            Tabs::make('general',__('General Information'),),
            Tabs::make('units',__('Units'), null),
            Tabs::make('gallery',__('Gallery'), null),
            // Tabs::make('front-desk',__('Front Desk'), null, true),
        ];
    }

    public function capsules() : array
    {
        return [
            Capsule::make('units', __('Property Units'), __('Units assigned to the property'), 'link', 'fa fa-home-user', route('properties.units.lists', ['property' => $this->property ? $this->property->id : null]), ['amount' => $this->property ? $this->property->units->count() : 0]),
            // Capsule::make('inventory-items', __('Inventory Items'), __('Inventory items assigned to the property'), 'link', 'fa fa-warehouse'),
            Capsule::make('tenants', __('Guest/Tenants'), __('Inventory items assigned to the property'), 'link', 'fa fa-users',   route($this->property->isHospitality() ? 'guests.lists' : 'tenants.lists', ['property' => $this->property ? $this->property->id : null]), ['amount' => 0]),
        ];
    }

    public function groups() : array
    {
        return [
            Group::make('general',__("Basic Details"), 'general'),
            // Group::make('general',__("General"), 'general')->component('app::form.tab.group.light'),
            Group::make('location',__("Location"), 'general'),
            Group::make('amenities',__("Amenities"), 'general'),
            Group::make('image-note',__("Images & Notes"), "")->component('app::form.tab.group.gallery-photo'),
            // Units
            Group::make('floors',__("Floors"), 'units')->component('app::form.tab.group.large-table'),
            Group::make('units',__("Units"), 'units')->component('app::form.tab.group.large-table'),
        ];
    }

    public function tables() : array
    {
        return  [
            // make($key, $label,$type, $tabs = null, $group = null)
            Table::make('floors',"Floors / Sections", 'units', 'floors', $this->property->floors ?? null),
            Table::make('units',"Info", 'units', 'units', $this->property->units ?? null),
            // Group::make('return',"Retours", 'general'),
        ];
    }

    public function columns() : array
    {
        return  [
            // make($key, $label)
            // Floors
            Column::make('name',"Name", 'floors'),
            Column::make('description',"Description", 'floors'),
            Column::make('status',"Status", 'floors'),
            // Units
            Column::make('name',"Name", 'units'),
            Column::make('name',"Unit No", 'units'),
            Column::make('property_unit_type_id',"Unit Type", 'units')->component('app::table.column.special.property-unit-type'),
            Column::make('property_unit_type_id',"Price", 'units')->component('app::table.column.special.unit-price'),
            Column::make('floor_id',"Floor/Section", 'units')->component('app::table.column.special.unit-floor'),
            Column::make('status',"Status", 'units'),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Property", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Nyumbani Heights'))->component('app::form.input.ke-title'),
            Input::make('property-type', 'Property Type', 'select', 'property_type', 'left', 'general', 'general', "", "", $this->typeOptions),
            Input::make('invoicing-type', 'Invoicing Type', 'select', 'invoicing', 'left', 'general', 'general', "", "", $this->invoiceOptions),
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'general', 'image-note', "", ""),
            // Rental Pricing
            // Input::make('base-rent', 'Base Rent', 'price', 'base_price', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental'])->component('app::form.input.depends'),
            // Input::make('payment-interval', 'Payment Interval', 'select', 'payment_interval', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental', 'data' => $this->paymentIntervalOptions])->component('app::form.input.depends'),
            // Input::make('deposit-amount', 'Deposit Amount', 'price', 'language', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental'])->component('app::form.input.depends'),
            // // Rate Pricing
            // Input::make('based-rate', 'Base Rate', 'price', 'base_price', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate'])->component('app::form.input.depends'),
            // Input::make('rate-frequency', 'Rate Frequency', 'select', 'payment_interval', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate', 'data' => $this->rateFrequencyOptions])->component('app::form.input.depends'),
            // Input::make('min-days', 'Minimum Stay Duration', 'number', 'stay', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate'])->component('app::form.input.depends'),
            // Location
            Input::make('address', null, 'select', 'address', 'left', 'general', 'location', "")->component('app::form.input.select.address'),
            // Amenities
            Input::make('amenities', null, 'tag', 'selectedAmenity', 'right', 'general', 'amenities', "", "", ['data' => $this->includedAmenities, 'options' => $this->amenityOptions, 'action' => 'addAmenity', 'delete' => 'removeAmenity']),

        ];
    }

    public function addAmenity()
    {
        // Validate that the selected feature exists and is unique for this unit type.
        $this->validate();

        if ($this->property && $this->selectedAmenity && !$this->property->amenities->contains('id', $this->selectedAmenity)) {
        $existingAmenity = PropertyAmenity::isCompany(current_company()->id)->isProperty($this->property->id)->isAmenity($this->selectedAmenity)->exists();

        if ($existingAmenity) {
                // You can add a message or handle the case where the feature is already selected.
                session()->flash('error', 'This amenity is already selected for the property.');
                return;
            }

            PropertyAmenity::create([
                'property_id' => $this->property->id,
                'amenity_id' => $this->selectedAmenity,
                'company_id' => current_company()->id,
            ]);
            // $this->loadFeatures();
            $this->selectedAmenity = null; // Reset the dropdown.
            $this->mount($this->property);
        }
    }

    public function removeAmenity($itemId)
    {
        // Find the amenity by its ID.
        $amenity = PropertyAmenity::where('id', $itemId)
            ->isProperty($this->property->id)
            ->first();

        if ($amenity) {
            $amenity->delete(); // Delete the amenity from the database.
            $this->selectedAmenity = null; // Reset the dropdown.
            $this->mount($this->property);
        }
    }

    #[On('create-property')]
    public function createUnit(){
        $this->validate();

        $property = Property::create([
            'name' => $this->name,
            'property_type_id' => $this->property_type,
            'invoicing_type' => $this->invoicing,
            'country_id' => $this->country,
            'state_id' => $this->state,
            'city' => $this->city,
            'zip' => $this->zip,
            'address' => $this->street,
            'description' => $this->description,
        ]);
        $property->save();
        return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);
    }

    #[On('update-property')]
    public function updateUnit(){
        $this->validate();
        $property = Property::find($this->property->id);

        $property->update([
            'name' => $this->name,
            'property_type_id' => $this->property_type,
            'invoicing_type' => $this->invoicing,
            'country_id' => $this->country,
            'state_id' => $this->state,
            'city' => $this->city,
            'zip' => $this->zip,
            'address' => $this->street,
            'description' => $this->description,
        ]);
        $property->save();
        return $this->redirect(route('properties.show', ['property' => $property->id]), navigate: true);
    }

}
