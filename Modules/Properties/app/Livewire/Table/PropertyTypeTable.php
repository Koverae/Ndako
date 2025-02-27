<?php

namespace Modules\Properties\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Property\PropertyType;

class PropertyTypeTable extends Table
{
    public array $data = [];

    public function mount(){
        $this->data = ['name', 'phone'];
    }

    public function createRoute() : string
    {
        return route('properties.types.create');
    }


    public function showRoute($id) : string
    {
        return route('properties.types.show', ['type' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'What’s Your Property Type?';
    }

    public function emptyText(): string
    {
        return 'Define your property types here to better organize and manage your listings for different categories.';
    }

    public function query() : Builder
    {
        return PropertyType::query(); // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
            Column::make('property_type_group', __('Type')),
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
