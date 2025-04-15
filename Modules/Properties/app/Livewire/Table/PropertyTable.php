<?php

namespace Modules\Properties\Livewire\Table;

use App\Models\User;
use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Traits\Table\HasSubData;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;

class PropertyTable extends Table
{
    use HasSubData;

    public array $data = [];

    public function mount(){
        $this->data = ['email', 'phone'];
    }

    public function createRoute() : string
    {
        return route('properties.create');
    }


    public function showRoute($id) : string
    {
        return route('properties.show', ['property' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'Your Property Awaits!';
    }

    public function emptyText(): string
    {
        return 'Add your first property to begin managing and organizing your listings effortlessly.';
    }

    public function query() : Builder
    {
        $query = Property::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            $query = Property::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('type', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where(function ($q) use ($field, $value) {
                    // Handle array of values or single
                    $values = is_array($value) ? $value : [$value];

                    // Check if column exists on the model's table
                    $model = $q->getModel();
                    $table = $model->getTable();

                    if (Schema::hasColumn($table, $field)) {
                        $q->whereIn($field, $values);
                    } else {
                        $q->orWhereHas('type', function ($subQuery) use ($field, $values) {
                            $subQuery->whereIn($field, $values);
                        });
                    }
                });
            }
        }


        return $query; // Returns a Builder instance for querying the User model
    }

    public function subQuery($propertyId = null): Builder
    {
        $query = PropertyUnit::query();

        // If a specific property ID is provided, filter by it.
        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        return $query;
    }

    public function subData($propertyId): \Illuminate\Support\Collection
    {
        // Fetch the sub-data for a specific property ID
        return $this->subQuery($propertyId)->get();
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
            Column::make('property_type_id', __('Property Type'))->component('app::table.column.special.property-type'),
            Column::make('id', __('Location'))->component('app::table.column.special.location'),
            Column::make('property_type_id', __('Operation Type'))->component('app::table.column.special.operation-type'),
            Column::make('status', __('Status')),
        ];
    }

    // List View
    public function subColumns() : array
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
