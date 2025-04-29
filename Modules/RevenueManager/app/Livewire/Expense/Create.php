<?php

namespace Modules\RevenueManager\Livewire\Expense;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        return view('revenuemanager::livewire.expense.create')->extends('layouts.app');
    }
}
