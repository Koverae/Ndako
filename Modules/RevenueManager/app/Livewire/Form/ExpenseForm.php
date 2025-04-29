<?php

namespace Modules\RevenueManager\Livewire\Form;

use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Modules\App\Livewire\Components\Form\Button\ActionBarButton;
use Modules\App\Livewire\Components\Form\Button\StatusBarButton;
use Modules\App\Livewire\Components\Form\Capsule;
use Modules\App\Livewire\Components\Form\Input;
use Modules\App\Livewire\Components\Form\Tabs;
use Modules\App\Livewire\Components\Form\Group;
use Modules\App\Livewire\Components\Form\Table;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Livewire\Components\Form\Template\LightWeightForm;
use Modules\App\Traits\Form\Button\ActionBarButton as ActionBarButtonTrait;
use Modules\RevenueManager\Models\Expenses\Expense;

class ExpenseForm extends LightWeightForm
{
    use ActionBarButtonTrait;

    public Expense $expense;

    public $reference, $title, $status, $date, $amount = 0;

    public function mount($expense = null){
        if($expense){
            $this->reference = $expense->reference;
            $this->title = $expense->title;
            $this->status = $expense->status;
            $this->date = $expense->date;
            $this->amount = $expense->amount;
            // $this->date = $expense->date;
        }
    }

    public function statusBarButtons() : array
    {
        return [
            StatusBarButton::make('pending', __('Draft'), 'draft'),
            StatusBarButton::make('paid', __('Posted'), 'posted'),
        ];
    }
}
