<?php

namespace Modules\RevenueManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\RevenueManager\Models\Expenses\Expense;

class ExpenseTable extends Table
{

    public array $data = [];  // Search query
    public $expenses;  // Users to display

    public function mount(){
        $this->data = ['title', 'description'];
    }

    public function emptyTitle(): string
    {
        return 'No Expense Yet';
    }

    public function emptyText(): string
    {
        return 'Expenses will be listed here once added.';
    }

    public function query($search = null) : Builder
    {
        $query = Expense::query();

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            $query = Expense::query()
                ->where('title', 'like', '%' . $this->searchQuery . '%')
                ->orWhere('reference', 'like', '%' . $this->searchQuery . '%');
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
            Column::make('reference', __('Reference')),
            Column::make('title', label: __('Title')),
            Column::make('expense_category_id', __('Category'))->component('app::table.column.special.expense.category'),
            Column::make('amount', __('Amount'))->component('app::table.column.special.price'),
            Column::make('date', __('Date'))->component('app::table.column.special.date.basic'),
            Column::make('status', __('Status')),
        ];
    }
}
