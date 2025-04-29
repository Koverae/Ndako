<?php

namespace Modules\RevenueManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\RevenueManager\Models\Expenses\ExpenseCategory;

class ExpenseCategoryTable extends Table
{
    
    public array $data = [];  // Search query
    public $categories;  // Users to display

    public function mount(){
        $this->data = ['name', 'description'];
    }

    public function emptyTitle(): string
    {
        return 'No Category Yet';
    }

    public function emptyText(): string
    {
        return 'Category will be listed here once added.';
    }

    public function query($search = null) : Builder
    {
        $query = ExpenseCategory::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            $query = ExpenseCategory::query()
                ->where('name', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
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
            Column::make('name', __('Name')),
            Column::make('description', __('Description')),
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
