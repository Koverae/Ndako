<?php

namespace Modules\Settings\Livewire\Table;

use App\Models\Company\Company;
use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;

class CompanyTable extends Table
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
        return 'No Company Added Yet';
    }

    public function emptyText(): string
    {
        return 'Add your first company to start organizing your business details and managing company-wide operations.';
    }

    public function query() : Builder
    {
        $query = Company::query();


        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        return $query;
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('name', __('Name'))->component('app::table.column.special.show-title-link'),
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
