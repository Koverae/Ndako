<?php

namespace Modules\RevenueManager\Livewire\Navbar\ControlPanel;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Modules\App\Livewire\Components\Navbar\Button\ActionButton;
use Modules\App\Livewire\Components\Navbar\Button\ActionDropdown;
use Modules\App\Livewire\Components\Navbar\ControlPanel;
use Modules\App\Livewire\Components\Navbar\SwitchButton;
use Modules\App\Services\ReportExportService;
use Modules\RevenueManager\Models\Expenses\Expense;

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
            ActionButton::make('import', 'Import Records', 'importRecords', false, "fas fa-upload"),
            ActionButton::make('export', 'Export All', 'exportAll', false, "fas fa-download"),
        ];
    }

    public function actionDropdowns(): array
    {
        return [
            ActionDropdown::make('export', 'Export', 'exportSelected', false, "fas fa-download"),
            // ActionDropdown::make('archive', 'Archive', 'archive', false, "fas fa-archive"),
            // ActionDropdown::make('unarchive', 'Unarchive', 'unarchive', false, "fas fa-inbox"),
            // ActionDropdown::make('duplicate', 'Duplicate', 'duplicateItems', false, "fas fa-copy"),
            ActionDropdown::make('delete', 'Delete', 'deleteSelectedItems', false, "fas fa-trash", true, "Do you really want to delete the selected items?"),
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

    public function exportAll(){
        $exportService = new ReportExportService();

        $expenses = Expense::isCompany(current_company()->id)->get()
        ->map(function ($expense) {

            return [
                'property' => $expense->property->name ?? "N/A",
                'unit' => $expense->unit->name ?? "N/A",
                'reference' => $expense->reference,
                'title' => $expense->title,
                'category' => $expense->category->name ?? "N/A",
                'amount' => $expense->amount,
                'date' => Carbon::parse($expense->date)->format('d/m/Y'),
            ];
        });

        $detailedSections = [
            'Expenses' => $expenses,
        ];

        return $exportService->export("Expenses_export", [], $detailedSections);
    }

    public function deleteSelectedItems(){

        Expense::isCompany(current_company()->id)
            ->whereIn('id', $this->selected)
            ->delete();

        LivewireAlert::title('Items deleted!')
        ->text('Selected items were deleted successfully!')
        ->success()
        ->position('top-end')
        ->timer(4000)
        ->toast()
        ->show();

        return $this->redirect(route('expenses.lists'), navigate:true);
    }

}
