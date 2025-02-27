<?php

namespace Modules\ChannelManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\ChannelManager\Models\Channel\Channel;

class ChannelTable extends Table
{
    public array $data = [];

    public function mount(){
        $this->data = ['integration_status', 'last_sync_date'];
    }

    // public function createRoute() : string
    // {
    //     return route('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return route('channels.show', ['channel' => $id]);
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
        return Channel::query()->isActive(); // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Channel Name'))->component('app::table.column.special.show-title-link'),
            Column::make('integration_status', __('Status'))->component('app::table.column.special.channel.integration-status'),
            Column::make('last_sync_date', __('Last Sync Date'))->component('app::table.column.special.date.timestamps'),
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
