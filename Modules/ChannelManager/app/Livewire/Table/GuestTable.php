<?php

namespace Modules\ChannelManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\ChannelManager\Models\Guest\Guest;

class GuestTable extends Table
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
        return route('channels.show', ['channel' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'No Guests Added';
    }

    public function emptyText(): string
    {
        return 'Guests will be listed here once added. Start by adding a guest to manage their stays and details easily.';
    }

    public function query() : Builder
    {
        $query = Guest::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = Guest::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
        }

        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        // 📦 Grouping (disabled for now)
        if (!empty($this->groupBy)) {
            foreach ($this->groupBy as $field => $value) {
                $query->groupBy($field);
            }
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name')),
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
