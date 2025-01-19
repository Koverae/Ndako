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

    public function mount(){
        $this->data = ['floor_name', 'phone'];
    }

    public function createRoute() : string
    {
        return Route::subdomainRoute('properties.units.create');
    }


    public function showRoute($id) : string
    {
        return Route::subdomainRoute('properties.units.show', ['unit' => $id]);
    }

    public function emptyTitle() : string
    {
        return '';
    }

    public function emptyText() : string
    {
        return '';
    }
    public function query() : Builder
    {
        return PropertyUnit::query(); // Returns a Builder instance for querying the User model
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
