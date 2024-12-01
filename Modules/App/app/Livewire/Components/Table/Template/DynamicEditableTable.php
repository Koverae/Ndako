<?php

namespace Modules\App\Livewire\Components\Table\Template;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;

abstract class DynamicEditableTable extends Component
{
    use WithPagination;

    public $view = 'lists';

    public $perPage = 10;

    public $page = 1;

    public $sortBy = '';

    public $sortDirection = 'asc';

    public $tables = [];

    public $editingIndex = null;

    public function render()
    {
        return view('app::livewire.components.table.template.dynamic-editable-table');
    }

    public function createRoute() : string{
        return '';
    }

    public function emptyTitle() : string{
        return '';
    }

    public function emptyText() : string{
        return '';
    }

    public function emptyButton() : string{
        return '';
    }

    public abstract function query() : Builder;

    public abstract function columns() : array;

    public function showRoute($id) : string{
        return '';
    }

    // public abstract function showRouteTwoVariable($id, $id_b);

    public function data()
    {
        return $this
            ->query()->isCompany(current_company()->id)
            ->when($this->sortBy !== '', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);
    }

    public function sort($key) {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            $this->sortDirection = $direction;

            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

}
