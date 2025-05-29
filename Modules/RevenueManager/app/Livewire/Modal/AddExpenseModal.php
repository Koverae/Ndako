<?php

namespace Modules\RevenueManager\Livewire\Modal;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;
use Modules\App\Events\NotificationEvent;
use Modules\App\Models\Notification\Notification;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\RevenueManager\Models\Expenses\Expense;
use Modules\RevenueManager\Models\Expenses\ExpenseCategory;

class AddExpenseModal extends ModalComponent
{

    public Expense $expense;

    public $property, $propertyUnit, $category, $reference, $name, $title, $status, $date, $amount = 0, $note;
    public $unitOptions, $categoryOptions;

    protected $rules = [
        'property' => 'required',
        'category' => 'required',
        'propertyUnit' => 'nullable',
        'name' => 'required|max:100',
        'date' => 'required',
        'amount' => 'required|max:100',
    ];
    public function mount($expense = null){
        $this->property = current_property()->id ?? null;
        $this->date = Carbon::today()->format('Y-m-d');

        if($expense){
            $this->reference = $expense->reference;
            $this->title = $expense->title;
            $this->status = $expense->status;
            $this->date = $expense->date;
            $this->amount = $expense->amount;
            $this->note = $expense->note;
        }

        $this->unitOptions = PropertyUnit::isCompany(current_company()->id)->isProperty($this->property)->get() ;
        $this->categoryOptions = ExpenseCategory::isCompany(current_company()->id)->get();
    }

    public function saveExpense(){
        // $this->validate();

        $expense = Expense::create([
            'company_id' => current_company()->id,
            'property_id' => $this->property,
            'property_unit_id' => $this->propertyUnit,
            'expense_category_id' => $this->category,
            'title' => $this->name,
            'amount' => $this->amount,
            'date' => $this->date,
            'note' => $this->note,
        ]);
        $expense->save();

        // Send Notification
        $notification = Notification::create([
            'user_id' => Auth::user()->id,
            'company_id' => $expense->company_id,
            'type' => 'expense.created',
            'data' => [
                'message' => "A new expense of ". format_currency($expense->amount). " has been made by {$expense->agent->name}.",
                'expense_id' => $expense->id,
            ],
        ]);

        event(new NotificationEvent($notification));

        $this->dispatch('closeModal');

        $this->redirect(route("expenses.lists"), true);

    }

    public function render()
    {
        return view('revenuemanager::livewire.modal.add-expense-modal');
    }
}
