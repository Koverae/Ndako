<?php

namespace Modules\App\Livewire\Components\Table;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\WithPagination;

abstract class Table extends Component
{
    use WithPagination;

    // Persist most-used UI state in the URL (non-breaking convenience)
    protected $queryString = [
        'page'          => ['except' => 1],
        'perPage'       => ['except' => 20],
        'sortBy'        => ['except' => ''],
        'sortDirection' => ['except' => 'asc'],
        'searchQuery'   => ['except' => ''],
        'view_type'     => ['except' => 'lists'],
    ];

    public $latitude = 51.505;
    public $longitude = -0.09;

    public string $searchQuery = '';
    public string $view_type = 'lists';
    public string $view = 'app::livewire.components.table.table';

    public array $components = [
        'lists'    => ['view' => 'app::livewire.components.table.table',            'component' => 'table-lists'],
        'kanban'   => ['view' => 'app::livewire.components.table.template.kanban',  'component' => 'kanban'],
        'map'      => ['view' => 'app::livewire.components.table.template.map',     'component' => 'map'],
        'calendar' => ['view' => 'app::livewire.components.table.template.calendar','component' => 'calendar'],
    ];

    public array $expandedRows = [];

    public int $perPage = 20;
    public int $page    = 1;
    public string $sortBy = '';
    public string $sortDirection = 'asc';
    public array $ids = [];        // kept for backward compatibility with your events
    public string $headerText = "Users";

    public bool $hasSubData = false;
    public array $selected = [];   // checkbox states keyed by id => true
    public array $filters  = [];
    public array $groupBy  = [];

    /** Customize this if you want fewer/more skeleton rows */
    public int $skeletonRows = 8;

    // --- Basic page/view lifecycle ------------------------------------------------

    // public function mount(): void
    // {
    //     // Ensure the initial view is valid
    //     if (!array_key_exists($this->view_type, $this->components)) {
    //         $this->view_type = 'lists';
    //     }
    //     $this->view = $this->components[$this->view_type]['view'];
    // }

    public function render()
    {
        return view($this->view);
    }

    // --- Metadata slots (unchanged API) ------------------------------------------

    public function headerName(): string   { return ''; }
    public function emptyTitle(): string   { return ''; }
    public function emptyText(): string    { return ''; }
    public function emptyButton(): string  { return ''; }
    public function createRoute(): string  { return ''; }

    public abstract function query(): Builder;
    public abstract function columns(): array;
    public function cards(): array { return []; }
    public function showRoute($id): string { return ''; }

    // --- Data + Sorting + Searching ----------------------------------------------

    public function data()
    {
        $q = $this->query()
            ->isCompany(current_company()->id)
            ->when(trim($this->searchQuery) !== '', function (Builder $query) {
                // Lightweight global search over declared columns (defaults to searchable=true)
                $query->where(function ($inner) {
                    foreach ($this->columns() as $col) {
                        $key = $col->key ?? null;
                        $searchable = property_exists($col, 'searchable') ? $col->searchable : true;
                        if ($key && $searchable) {
                            $inner->orWhere($key, 'like', '%' . $this->searchQuery . '%');
                        }
                    }
                });
            })
            ->when($this->sortBy !== '', function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            });

        // Optional: apply incoming filters array without forcing a format
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                if ($value === null || $value === '') continue;
                $q->where($field, $value);
            }
        }

        return $q->paginate($this->perPage);
    }

    public function sort($key): void
    {
        $this->resetPage();

        if ($this->sortBy === $key) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $key;
        $this->sortDirection = 'asc';
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    // --- Selection (fixed + smoother) --------------------------------------------

    // Toggle a single row checkbox; keeps $ids and $selected in sync

      public function toggleCheckbox($id)
      {
          // If the checkbox is checked, add the id to the array
          if (in_array($id, $this->ids)) {
              $this->ids = array_diff($this->ids, [$id]);
          } else {
              $this->ids[] = $id;
          }

          $this->dispatch('updatedSelected', selected: $this->ids);
      }

    public function isPageFullySelected(): bool
    {
        $visibleIds = $this->data()->pluck('id')->all();
        if (empty($visibleIds)) return false;

        $selectedOnPage = array_intersect($visibleIds, $this->ids);
        return count($selectedOnPage) === count($visibleIds);
    }

    public function toggleSelectAll(): void
    {
        $visibleIds = $this->data()->pluck('id')->all();

        if ($this->isPageFullySelected()) {
            foreach ($visibleIds as $id) {
                unset($this->selected[$id]);
            }
        } else {
            foreach ($visibleIds as $id) {
                $this->selected[$id] = true;
            }
        }

        $this->ids = array_keys($this->selected);
        $this->dispatch('updatedSelected', selected: $this->ids);
    }

    #[On('emptyArray')]
    public function emptyArray(): void
    {
        $this->ids = [];
        $this->selected = [];
    }

    // --- Filters & Search ---------------------------------------------------------

    #[On('updateFilters')]
    public function updateFilters($filters): void
    {
        $this->filters = $filters ?? [];
        $this->resetPage();
    }

    #[On('update-search')]
    public function updateSearch($search): void
    {
        $this->searchQuery = (string) $search;
        $this->resetPage();
        // No need to call $this->query(); Livewire will re-render.
    }

    // --- View switching (unchanged UX) -------------------------------------------

    #[On('switch-view')]
    public function switchView($view): void
    {
        $this->view_type = $view;
        if (array_key_exists($view, $this->components)) {
            $this->view = $this->components[$view]['view'];
            if ($this->view_type === 'calendar') {
                $this->dispatch('calendarUpdated');
            }
        } else {
            abort(404, 'Component not found.');
        }
    }

    // --- Map helper ---------------------------------------------------------------

    public function updateMap($lat, $lng): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->dispatchBrowserEvent('map-updated', ['lat' => $lat, 'lng' => $lng]);
    }

    // --- Row expansion ------------------------------------------------------------

    public function toggleRowExpansion($rowId): void
    {
        if (in_array($rowId, $this->expandedRows)) {
            $this->expandedRows = array_values(array_diff($this->expandedRows, [$rowId]));
        } else {
            $this->expandedRows[] = $rowId;
        }
    }
}
