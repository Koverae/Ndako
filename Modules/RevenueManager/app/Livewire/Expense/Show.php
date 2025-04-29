<?php

namespace Modules\RevenueManager\Livewire\Expense;

use Livewire\Component;
use Modules\RevenueManager\Models\Expenses\Expense;

class Show extends Component
{
    public Expense $expense;

    public function mount(Expense $expense){
        $this->expense = $expense;
    }

    public function render()
    {
        return view('revenuemanager::livewire.expense.show')
        ->extends('layouts.app');
    }
}
