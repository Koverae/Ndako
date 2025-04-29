<?php

namespace Modules\RevenueManager\Livewire\Expense;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('revenuemanager::livewire.expense.lists')->extends('layouts.app');
    }
}
