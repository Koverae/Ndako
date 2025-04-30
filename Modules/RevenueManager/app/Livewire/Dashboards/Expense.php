<?php

namespace Modules\RevenueManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
use Modules\RevenueManager\Models\Expenses\Expense as ExpensesModel;
use Modules\RevenueManager\Models\Expenses\ExpenseCategory;

class Expense extends Component
{
    public $period = 7, $property;
    public $spentAmount = 0, $unpaidAmount = 0, $averageSpentAmount = 0, $numberOfExpenses = 0;
    public $properties, $units, $unitTypes, $mothlyInvoices, $bestCategory, $expenses, $expenseCategories, $rooms;
    public $startDate, $endDate;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = current_property()->id ?? null;

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
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
        ->select(
            DB::raw('SUM(amount) as total_spent'),
            // DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $expenses = ExpensesModel::isCompany(current_company()->id)
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
        ->select(
            DB::raw('SUM(amount) as total_spent'),
            // DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $this->spentAmount = $expenses->total_spent ?? 0;
        $this->unpaidAmount = $pendingExpenses->total_spent ?? 0;

        $expenseStats = ExpensesModel::isCompany(current_company()->id)
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
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
            $query->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()]);
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
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
        ->get()
        ->sortByDesc('amount');

        // Rooms
        $this->rooms = PropertyUnit::isCompany(current_company()->id)
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['bookings' => function ($query) {
            $query->select('id', 'property_unit_id', 'total_amount', DB::raw('DATEDIFF(check_out, check_in) as nights'))
            ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
            ->orWhereBetween('check_out', [Carbon::now()->subDays($this->period), Carbon::now()]);
        }])
        ->get()
        ->map(function ($room) {
            $totalRevenue = $room->bookings->sum('total_amount');
            $totalNights = $room->bookings->sum('nights');

            return [
                'room_name' => $room->name,
                'total_revenue' => $totalRevenue,
                'total_nights' => $totalNights,
            ];
        })
        ->sortByDesc('total_revenue') // Sort by revenue descending
        ->values(); // Re-index the collection

    }

    public function render()
    {
        return view('revenuemanager::livewire.dashboards.expense');
    }
}
