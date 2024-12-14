<?php

namespace Modules\App\Livewire\Components\Table;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\WithPagination;

abstract class Table extends Component
{
    use WithPagination;

    public $latitude = 51.505;
    public $longitude = -0.09;

    public $view_type = 'lists';
    public $view = 'app::livewire.components.table.table';
    public $components = [
        'lists' => [
            'view' => 'app::livewire.components.table.table',
            'component' => 'table-lists',
        ],
        'kanban' => [
            'view' => 'app::livewire.components.table.template.kanban',
            'component' => 'kanban',
        ],
        'map' => [
            'view' => 'app::livewire.components.table.template.map',
            'component' => 'map',
        ],
    ];

    public $perPage = 20;

    public $page = 1;

    public $sortBy = '';

    public $sortDirection = 'asc';

    public $ids = [];
    public $headerText = "Users";


    public function render()
    {
        // return view("app::livewire.components.table.template.map");
        return view($this->view);
    }

    public function headerName() : string{
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

    public function createRoute() : string{
        return '';
    }

    public abstract function query() : Builder;

    public abstract function columns() : array;


    public function cards() : array{
        return [];
    }

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

      public function toggleCheckbox($id)
      {
          // If the checkbox is checked, add the id to the array
          if (in_array($id, $this->ids)) {
              $this->ids = array_diff($this->ids, [$id]);
          } else {
              $this->ids[] = $id;
          }
        // Toggle the presence of the ID in the array
        // if (in_array($id, $this->ids)) {
        //     $this->ids = array_values(array_diff($this->ids, [$id])); // Remove the ID if it's already present
        // } else {
        //     $this->ids[] = $id; // Add the ID if it's not present
        // }
      }

      public function emptyArray()
      {
          // Empty the $ids array
          $this->ids = [];
      }

      #[On('switch-view')]
      public function switchView($view)
      {
          $this->view_type = $view;
          // Check if the component type exists in the components array
          if(array_key_exists($view, $this->components)){
            // Set the view from the components array
            $this->view = $this->components[$view]['view'];
          }else{
            // Handle the case when the component type doesn't exist
            abort(404, 'Component not found.');
          }
      }

    public function updateMap($lat, $lng)
    {
        $this->latitude = $lat;
        $this->longitude = $lng;

        // You can trigger an event to update the map on the frontend
        $this->dispatchBrowserEvent('map-updated', ['lat' => $lat, 'lng' => $lng]);
    }
}
