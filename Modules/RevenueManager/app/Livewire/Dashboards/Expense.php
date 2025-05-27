<?php

namespace Modules\RevenueManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
use Modules\RevenueManager\Models\Expenses\Expense as ExpensesModel;
use Modules\RevenueManager\Models\Expenses\ExpenseCategory;

class Expense extends Component
{
    public $period = 1, $property;
    public $spentAmount = 0, $unpaidAmount = 0, $averageSpentAmount = 0, $numberOfExpenses = 0;
    public $properties, $units, $unitTypes, $monthlyExpenses, $bestCategory, $expenses, $expenseCategories, $rooms, $expenseByCategory;
    public $startDate, $endDate;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = current_property()->id ?? null;

        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addDays($this->period)->format('Y-m-d');

        $this->loadData();
    }

    public function loadData($property = null){
        if($property){
            $this->property = $property;
        }

        // Define the date range (e.g., last 7 days)
        $startDate = Carbon::now()->subDays($this->period ?? 7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $pendingExpenses = ExpensesModel::isCompany(current_company()->id)
        ->where('status', 'pending')
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->select(
            DB::raw('SUM(amount) as total_spent'),
            // DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $expenses = ExpensesModel::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->select(
            DB::raw('SUM(amount) as total_spent'),
            // DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $this->spentAmount = $expenses->total_spent ?? 0;
        $this->unpaidAmount = $pendingExpenses->total_spent ?? 0;

        $expenseStats = ExpensesModel::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->with(['property' => function ($query) {
            $query->when($this->property, function ($property){
                $property->where('property_id', $this->property);
            });
        }])
        ->select(
            DB::raw('AVG(amount) as average_spent_amount'),
            DB::raw('COUNT(id) as number_of_expenses')
        )
        ->first();

        $this->averageSpentAmount = round($expenseStats->average_spent_amount) ?? 0;
        $this->numberOfExpenses = $expenseStats->number_of_expenses ?? 0;

        $this->expenseCategories = ExpenseCategory::isCompany(current_company()->id)
        ->with(['expenses' => function ($query) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }])
        ->get()
        ->map( function ($category) {

            $totalSpent = $category->expenses->sum('amount');
            $totalExpenses = $category->expenses->count(); // Count actual expense records

            return [
                'category_name' => $category->name,
                'total_amount' => $totalSpent,
                'expenses' => $totalExpenses,
            ];
        })
        ->sortByDesc('total_amount') // Sort by revenue descending
        ->values(); // Re-index the collection

        $this->bestCategory = $this->expenseCategories->first();

        // Expenses
        $this->expenses = ExpensesModel::isCompany(current_company()->id)
        ->with(['property' => function ($query) {
            $query->when($this->property, function ($property){
                $property->where('id', $this->property);
            });
        }])
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->get()
        ->sortByDesc('amount');

        // Rooms
        $this->rooms = PropertyUnit::isCompany(current_company()->id)
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['expenses' => function ($query) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }])
        ->get()
        ->map(function ($room) {
            $totalSpent = $room->expenses->sum('amount');
            $totalExpenses = $room->expenses->count();

            return [
                'room_name' => $room->name,
                'room_type' => $room->unitType->name,
                'total_amount' => $totalSpent,
                'total_expenses' => $totalExpenses,
            ];
        })
        ->sortByDesc('total_amount') // Sort by revenue descending
        ->values(); // Re-index the collection

        // Monthly Expenses
        $this->monthlyExpenses = $this->getMonthlyExpensess();

        $this->fetchExpenseByCategory();

    }

    public function updatedPeriod(){
        $this->loadData();
    }

    public function updatedStartDate($property){
        
        if (Carbon::parse($this->startDate)->gt($this->endDate)) {
            // Start date is after end date
            session()->flash('error', 'Start date must be before end date.');
        } else {
            $this->loadData();
        }

    }

    public function updatedEndDate($property){
        
        if (Carbon::parse($this->startDate)->gt($this->endDate)) {
            // Start date is after end date
            session()->flash('error', 'Start date must be before end date.');
        } else {
            $this->loadData();
        }
    }
    
    public function getMonthlyExpensess(): \Illuminate\Support\Collection
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $expenses = ExpensesModel::with(['unit' => function ($subQuery) {
                $subQuery->when($this->property, function ($property){
                    $property->where('property_id', $this->property);
                });
            }])
            ->whereBetween('date', [$startOfYear, $endOfYear])
                ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(amount) as total_amount')
                    ->groupBy('year', 'month')
                        ->orderByRaw('year ASC, month ASC')
                            ->get();

        return $expenses->map(fn ($expense) => [
            'month'   => Carbon::create($expense->year, $expense->month, 1)->format('F Y'),
            'spent' => round((float) $expense->total_amount, 2),
        ]);
    }

    // Export Dashboard
    public function export(ReportExportService $exportService)
    {

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Total Expenses' => ['value' => format_currency($this->spentAmount), 'change' => format_currency($this->unpaidAmount)],
            'Average Expense' => ['value' => format_currency($this->averageSpentAmount), 'change' => $this->numberOfExpenses],
            'Top Spending Category' => ['value' => $this->bestCategory['category_name'], 'change' => $this->bestCategory['total_amount'] ?? 0],
        ];

        $topExpenses = $this->expenses->map(function ($expense) {
            return [
                'reference' => $expense->reference,
                'title' => $expense->title,
                'agent' => $expense->agent->name ?? 'N/A',
                'status' => $expense->status,
                'date' => Carbon::parse($expense->date)->format('m/d/y') ?? 'N/A',
                'amount' => format_currency($expense->amount)
            ];
        })
        ->sortByDesc('revenue');

        $topExpenseCategories = $this->expenseCategories;

        // Assign to detailed sections
        $detailedSections = [
            'Top Expenses' => $topExpenses,
            'Top Expense Categories' => $topExpenseCategories,
        ];

        // ✅ Export Report
        return $exportService->export('Expense Report', $summaryData, $detailedSections, 'xlsx');
    }


    public function fetchExpenseByCategory()
    {
        $startDate = Carbon::now()->subDays($this->period);
        $endDate = Carbon::now();

        $this->expenseByCategory = ExpenseCategory::isCompany(current_company()->id)
        ->with(['expenses' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }])
        ->get()
            ->map( function ($category) {

                $totalSpent = $category->expenses->sum('amount');
                $totalExpenses = $category->expenses->count(); // Count actual expense records

                return [
                    'label' => $category->name,
                    'value' => $totalSpent,
                ];
            })
            // ->sortByDesc('value') // Sort by revenue descending
            ->values(); // Reset array keys
        
    }


    public function render()
    {
        return view('revenuemanager::livewire.dashboards.expense', [
            'expenseCategoryChartData' => [
                'labels' => $this->expenseByCategory->pluck('label')->toArray(),
                'series' => $this->expenseByCategory->pluck('value')->toArray(),
            ]
        ]);
    }
}
