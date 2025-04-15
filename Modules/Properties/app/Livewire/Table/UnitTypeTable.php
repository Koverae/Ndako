<?php

namespace Modules\Properties\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Property\PropertyUnitType;

class UnitTypeTable extends Table
{
    public array $data = [];

    public function mount(){
        $this->data = ['floor_name', 'phone'];
    }

    public function createRoute() : string
    {
        return route('properties.unit-types.create');
    }


    public function showRoute($id) : string
    {
        return route('properties.unit-types.show', ['type' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'Define Your Spaces!';
    }

    public function emptyText(): string
    {
        return 'Add a unit type to categorize your rooms or properties. It’s the first step in creating a tailored experience for your guests.';
    }

    public function query() : Builder
    {
        $query = PropertyUnitType::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            $query = PropertyUnitType::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('property', function($query) {
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
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
            Column::make('property_id', __('Property'))->component('app::table.column.special.property'),
            Column::make('id', __('Price'))->component('app::table.column.special.unit-price'),
            // Column::make('status', __('Status')),
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
