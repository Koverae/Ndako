<?php

namespace Modules\Settings\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Settings\Models\WorkItem;

class WorkItemTable extends Table
{
    public array $data = [];

    public function mount(){
        $this->data = ['email', 'phone', 'address'];
    }

    public function createRoute() : string
    {
        return route('settings.companies.create');
    }


    public function showRoute($id) : string
    {
        return route('settings.companies.show', ['company' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'No Maintenance Requests Yet';
    }
    
    public function emptyText(): string
    {
        return 'Maintenance requests will appear here once submitted. Add a request to stay on top of property upkeep and keep things running smoothly.';
    }

    public function query() : Builder
    {
        $query = WorkItem::query()->isTasks();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = WorkItem::query()
            ->where('title', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('assignedTo', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            })
            ->orWhereHas('unit', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('title', __('Title')),
            Column::make('description', __('Description')),
            Column::make('room_id', __('Room'))->component('app::table.column.special.property-unit'),
            Column::make('assigned_to', __('Assigned To'))->component('app::table.column.special.contact.user'),
            Column::make('status', __('Status'))->component('app::table.column.special.booking.booking-status'),
        ];
    }

    // Kanban View
    public function cards() : array
    {
        return [
            Card::make('name', "name", "", $this->data)->component(''),
        ];
    }
}
