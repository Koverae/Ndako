<?php

namespace Modules\RevenueManager\Livewire\ExpenseCategory;

use Livewire\Component;

class Lists extends Component
{
    public function render()
    {
        return view('revenuemanager::livewire.expense-category.lists')->extends('layouts.app');
    }
}
