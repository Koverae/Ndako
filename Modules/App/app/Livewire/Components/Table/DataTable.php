<?php

namespace Modules\App\Livewire\Components\Table;

use Livewire\Component;
use Livewire\WithPagination;

class DataTable extends Component
{
    use WithPagination;

    public $modelInstance;

    public $view = 'lists';

    public $show = 10;  // Default number of employees to show

    public $selectedContact = []; //Checkbox select

    public $deleteId = '';

    public function changeView($view){
        $this->view = $view;
    }


    public function render()
    {
        $data = $this->modelInstance::isCompany(current_company()->id)->paginate($this->show);

        return view('app::livewire.components.table.data-table', [
            'data' => $data
        ]);
    }
}
