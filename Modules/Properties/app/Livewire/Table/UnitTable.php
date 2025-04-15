<?php

namespace Modules\Properties\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Property\PropertyUnit;

class UnitTable extends Table
{
    public array $data = [];
    public $propertyID;

    public function mount(){
        $this->data = ['floor_name', 'phone'];
        $this->propertyID = request()->query('property', null);
    }

    public function createRoute() : string
    {
        return route('properties.units.create');
    }


    public function showRoute($id) : string
    {
        return route('properties.units.show', ['unit' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'Your Space is Waiting!';
    }

    public function emptyText(): string
    {
        return 'Add your first unit to start managing your property with ease. Each unit helps you track availability, reservations, and more.';
    }

    public function query() : Builder
    {
        $query = PropertyUnit::query();

        // Filter by property ID from the URL
        if ($this->propertyID) {
            $query->isProperty($this->propertyID);
        }

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = PropertyUnit::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('unitType', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Unit Name'))->component('app::table.column.special.show-title-link'),
            Column::make('name', __('Unit No'))->component('app::table.column.special.show-title-link'),
            Column::make('property_unit_type_id', __('Unit Type'))->component('app::table.column.special.property-unit-type'),
            Column::make('floor_id', __('Floor/Section'))->component('app::table.column.special.unit-floor'),
            Column::make('property_unit_type_id', __('Price'))->component('app::table.column.special.unit-price'),
            Column::make('status', __('Status')),
        ];
    }

    // Kanban View
    public function cards() : array
    {
        return [
            Card::make('name', "name", "", $this->data),
        ];
    }
}
