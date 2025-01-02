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
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Property\Amenity;
use Modules\Properties\Models\Property\PropertyAmenity;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyType;

class PropertyForm extends SimpleAvatarForm
{
    public $property;
    public $property_type, $invoicing, $name, $country, $street, $city, $state, $zip, $description;
    public $base_price = 25000, $payment_interval = 'monthly', $stay = 1;
    public array $typeOptions = [], $invoiceOptions = [], $paymentIntervalOptions = [], $propertyAmenitiesOptions = [], $amenityOptions, $rateFrequencyOptions = [];

    public function mount($property = null){
        $this->default_img = 'placeholder';
        if($property){
            $this->property = $property;
            $this->name = $property->name;
            $this->property_type = $property->property_type_id;
            $this->invoicing = $property->invoicing_type;
            $this->country = $property->country_id;
            $this->country = $property->country_id;
            $this->state = $property->state_id;
            $this->city = $property->city;
            $this->zip = $property->zip;
            $this->street = $property->address;
            $this->description = $property->description;

            $this->propertyAmenitiesOptions = toSelectOptions(PropertyAmenity::isProperty($property->id)->get(), 'id', 'id');
        }

        $this->typeOptions = toSelectOptions(PropertyType::isCompany(current_company()->id)->get(), 'id', 'name');

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

        $this->amenityOptions = toSelectOptions(Amenity::isCompany(current_company()->id)->get(), 'id', 'name');
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
            Capsule::make('on_hand', __('Property Units'), __('Inventory items assigned to the property'), 'link', 'fa fa-home-user'),
            Capsule::make('inventory-items', __('Inventory Items'), __('Inventory items assigned to the property'), 'link', 'fa fa-warehouse'),
            Capsule::make('tenants', __('Tenants'), __('Inventory items assigned to the property'), 'link', 'fa fa-users'),
        ];
    }

    public function groups() : array
    {
        return [
            Group::make('general',__("General"), 'general')->component('app::form.tab.group.light'),
            // Group::make('general',__("General"), 'general')->component('app::form.tab.group.light'),
            Group::make('location',__("Location"), 'general'),
            Group::make('amenities',__("Amenities"), 'general'),
            // Units
            Group::make('floors',__("Floors"), 'units')->component('app::form.tab.group.large-table'),
            Group::make('units',__("Units"), 'units')->component('app::form.tab.group.large-table'),
            // Gallery
            Group::make('photos',__("Photos"), 'gallery')->component('app::form.tab.group.gallery-photo'),
            Group::make('videos',__("Videos"), 'gallery')->component('app::form.tab.group.gallery-photo'),
            Group::make('virtual-tours',__("Virtual Tours"), 'gallery')->component('app::form.tab.group.gallery-photo'),
        ];
    }

    public function tables() : array
    {
        return  [
            // make($key, $label,$type, $tabs = null, $group = null)
            Table::make('floors',"Floors", 'units', 'floors', PropertyFloor::isCompany(current_company()->id)->get()),
            Table::make('units',"Info", 'units', 'units', Amenity::isCompany(current_company()->id)->get()),
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
            Column::make('name',"Unit Type", 'units'),
            Column::make('price',"Price", 'units'),
            Column::make('name',"Floor/Section", 'units'),
            Column::make('status',"Status", 'units'),
        ];
    }

    public function inputs(): array
    {
        return [
            Input::make('name', "Property", 'text', 'name', 'top-title', 'none', 'none', __('e.g. Nyumbani Heights'))->component('app::form.input.ke-title'),
            Input::make('property-type', 'Property Type', 'select', 'property_type', 'left', 'general', 'general', "", "", $this->typeOptions),
            Input::make('invoicing-type', 'Invoicing Type', 'select', 'invoicing', 'left', 'general', 'general', "", "", $this->invoiceOptions),
            Input::make('description', 'Description', 'textarea', 'description', 'left', 'general', 'general', "", ""),
            // Rental Pricing
            Input::make('base-rent', 'Base Rent', 'price', 'base_price', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental'])->component('app::form.input.depends'),
            Input::make('payment-interval', 'Payment Interval', 'select', 'payment_interval', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental', 'data' => $this->paymentIntervalOptions])->component('app::form.input.depends'),
            Input::make('deposit-amount', 'Deposit Amount', 'price', 'language', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rental'])->component('app::form.input.depends'),
            // Rate Pricing
            Input::make('based-rate', 'Base Rate', 'price', 'base_price', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate'])->component('app::form.input.depends'),
            Input::make('rate-frequency', 'Rate Frequency', 'select', 'payment_interval', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate', 'data' => $this->rateFrequencyOptions])->component('app::form.input.depends'),
            Input::make('min-days', 'Minimum Stay Duration', 'number', 'stay', 'right', 'general', 'general', "", "", ['parent' => $this->invoicing == 'rate'])->component('app::form.input.depends'),
            // Location
            Input::make('address', null, 'select', 'address', 'left', 'general', 'location', "")->component('app::form.input.select.address'),
            // Amenities
            Input::make('amenities', null, 'tag', 'address', 'right', 'general', 'amenities', "", "", ['data' => $this->propertyAmenitiesOptions, 'options' => $this->amenityOptions]),

        ];
    }

}
