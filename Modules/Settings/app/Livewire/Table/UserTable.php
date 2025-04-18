<?php

namespace Modules\Settings\Livewire\Table;

use App\Models\User;
use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;

class UserTable extends Table
{
    public array $data = [];  // Search query
    public $users;  // Users to display

    public function mount(){
        $this->data = ['email', 'phone'];
    }

    public function createRoute() : string
    {
        return route('settings.users.create');
    }


    public function showRoute($id) : string
    {
        return route('settings.users.show', ['user' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'No Users Yet';
    }

    public function emptyText(): string
    {
        return 'Users will be listed here once added. Start by adding your first user to begin managing access and roles.';
    }

    public function query($search = null) : Builder
    {
        $query = User::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            $query = User::query()
                ->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
        }

        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        return  $query;// Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
            Column::make('email', __('Email')),
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
