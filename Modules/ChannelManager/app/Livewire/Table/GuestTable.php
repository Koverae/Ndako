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
        $this->data = ['integration_status', 'last_sync_date'];
    }

    // public function createRoute() : string
    // {
    //     return Route::subdomainRoute('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return Route::subdomainRoute('channels.show', ['channel' => $id]);
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
        return Guest::query(); // Returns a Builder instance for querying the User model
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
