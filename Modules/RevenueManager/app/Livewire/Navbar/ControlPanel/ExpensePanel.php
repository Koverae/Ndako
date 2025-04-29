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
        $this->new = route('expenses.create');
        if($category){
            $this->showIndicators = true;
            $this->category = $category;
            $this->isForm = true;
            $this->currentPage = $category->name;
        }else{
            $this->currentPage = "Expenses";
        }

    }

    public function switchButtons() : array
    {
        return  [
            // make($key, $label)
            SwitchButton::make('lists',"switchView('lists')", "bi-list-task"),
        ];
    }
}
