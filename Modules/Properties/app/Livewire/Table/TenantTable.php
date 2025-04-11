<?php

namespace Modules\Properties\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Properties\Models\Tenant\Tenant;

class TenantTable extends Table
{
    public array $data = [];


    public function mount(){
        $this->data = ['email', 'phone', 'street'];
    }

    // public function createRoute() : string
    // {
    //     return route('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return route('tenants.show', ['tenant' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'No Tenants Added';
    }

    public function emptyText(): string
    {
        return 'Tenants will be listed here once added. Start by adding a tenant to manage their leases and details easily.';
    }

    public function query() : Builder
    {
        $query = Tenant::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = Tenant::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
            Column::make('email', __('Email')),
            Column::make('street', __('Address')),
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
