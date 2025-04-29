<?php

namespace Modules\RevenueManager\Livewire\Modal;

use LivewireUI\Modal\ModalComponent;
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
        $this->property = current_property()->id;
        if($expense){
            $this->reference = $expense->reference;
            $this->title = $expense->title;
            $this->status = $expense->status;
            $this->date = $expense->date;
            $this->amount = $expense->amount;
            $this->note = $expense->note;
        }

        $this->unitOptions = PropertyUnit::isCompany(current_company()->id)->isProperty($this->property)->get();
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

        $this->dispatch('closeModal');

    }

    public function render()
    {
        return view('revenuemanager::livewire.modal.add-expense-modal');
    }
}
