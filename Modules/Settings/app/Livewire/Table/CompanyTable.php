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
        return Route::subdomainRoute('settings.companies.create');
    }


    public function showRoute($id) : string
    {
        return Route::subdomainRoute('settings.companies.show', ['company' => $id]);
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
        return Company::query(); // Returns a Builder instance for querying the User model
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
