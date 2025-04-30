<?php

namespace Modules\RevenueManager\Livewire\Navbar\ControlPanel;

use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;

class ExpensePanel extends ControlPanel
{
    public $category;

    public function mount($category = null, $isForm = false)
    {
        $this->showBreadcrumbs = true;
        $this->generateBreadcrumbs();
        $this->newModal = "newExpense";
        if($category){
            $this->showIndicators = true;
            $this->category = $category;
            $this->isForm = true;
            $this->currentPage = $category->name;
        }else{
            $this->currentPage = "Expenses";
        }

    }

    public function actionButtons(): array
    {
        return [
            // ActionButton::make('export', 'Export All', 'exportAll', false, "fas fa-download"),
            ActionButton::make('import', 'Import Records', 'importRecords', false, "fas fa-upload"),
        ];
    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
        ];
    }

    // New expense modal
    public function newExpense(){
        $this->dispatch('openModal', component: 'revenuemanager::modal.add-expense-modal');
    }
    

    public function importRecords(){
        return $this->redirect(route('import.records', 'mod_expenses'), true);
    }

}
