<?php

namespace Modules\Settings\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\Settings\Models\Role\Role;

class RolePermissionTable extends Table
{
    public array $data = [];

    public function showRoute($id) : string
    {
        return route('roles.lists', ['role' => $id]);
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
        $query = Role::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = Role::query()
            ->where('name', 'like', '%' . $this->searchQuery . '%');
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name')),
        ];
    }

}
