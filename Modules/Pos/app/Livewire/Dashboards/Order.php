<?php

namespace Modules\Pos\Livewire\Dashboards;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
use Modules\Pos\Models\Floor\FloorPlan;
use Modules\Pos\Models\Order\PosOrder;
use Modules\Pos\Models\Order\PosOrderPayment;
use Modules\Pos\Models\Pos\Pos;
use Modules\Pos\Models\Pos\PosSession;
use Modules\Pos\Models\Product\Product;
use Modules\Pos\Models\Product\ProductCategory;

class Order extends Component
{
    public $period = 1, $restaurant;
    public $soldAmount, $unpaidAmount, $averageOrderAmount, $numberOfOrders, $dso, $orders, $payments;
    public $restaurants, $floors, $unitTypes, $mothlyOrders, $bestCategories, $bestProducts, $bestPosSessions;
    public $startDate, $endDate;

    public function mount(){
        $this->restaurants = Pos::isCompany(current_company()->id)->get();
        $this->restaurant = current_property()->id ?? null;

        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addDays($this->period)->format('Y-m-d');

        $this->loadData();
    }

    public function loadData($restaurant = null){
        if($restaurant){
            $this->restaurant = $restaurant;
        }

        $this->restaurant = $restaurant;
        $this->floors = FloorPlan::isCompany(current_company()->id)->isPos($this->restaurant)->get();

        $orders = PosOrder::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->select(
            DB::raw('SUM(total_amount) as total_amount'),
            DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $this->soldAmount = $orders->total_amount ?? 0;
        $this->unpaidAmount = $orders->total_unpaid ?? 0;

        $orderStats = PosOrder::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->select(
            DB::raw('AVG(total_amount) as average_order_amount'),
            DB::raw('COUNT(id) as number_of_orders')
        )
        ->first();

        $this->averageOrderAmount = round($orderStats->average_order_amount) ?? 0;
        $this->numberOfOrders = $orderStats->number_of_orders ?? 0;


        // Number of days for the period (e.g., last 30 days)
        $daysInPeriod = 365; // Change as necessary (e.g., 7, 30, 365)

        // Calculate DSO
        if ($this->soldAmount > 0) {
            $this->dso = round(($this->unpaidAmount / $this->soldAmount) * $daysInPeriod);
        } else {
            $this->dso = 0; // Avoid division by zero
        }

        $this->orders = PosOrder::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
           ->when($this->restaurant, function ($query) {
                $query->where('pos_id', $this->restaurant);
            })
            ->orderByDesc('total_amount')
                ->get();

        $this->payments = PosOrderPayment::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->when($this->restaurant, function ($query) {
            $query->with('order', function ($query) {
                $query->where('pos_id', $this->restaurant);
            });
        })
        ->orderByDesc('amount')
        ->get();

        $this->mothlyOrders = $this->getMonthlyOrders();

        // Fetch room types with aggregated booking revenue
        $this->bestCategories = ProductCategory::isCompany(current_company()->id)
            ->when($this->restaurant, function ($query) {
            $query->where('pos_id', $this->restaurant);
            })
            ->with(['products' => function ($query) {
            $query->withCount(['details as details_count' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }])
                ->withSum(['details as details_sum_sub_total' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }], 'sub_total');
            }])
            ->get()
            ->map(function ($category) {
            $totalRevenue = $category->products->sum('details_sum_sub_total') ?? 0;
            $totalOrders = $category->products->sum('details_count') ?? 0;

            return [
                'name' => $category->name,
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
            ];
            })
            ->sortByDesc('total_revenue')
            ->values();

            // Fetch best selling products with aggregated revenue and order count
            $this->bestProducts = Product::isCompany(current_company()->id)
                ->when($this->restaurant, function ($query) {
                    $query->where('pos_id', $this->restaurant);
                })
                ->withCount(['details as details_count' => function ($subQuery) {
                    $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }])
                ->withSum(['details as details_sum_sub_total' => function ($subQuery) {
                    $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }], 'sub_total')
                ->get()
                ->map(function ($product) {
                    return [
                        'name' => $product->product_name,
                        'total_orders' => $product->details_count ?? 0,
                        'total_revenue' => $product->details_sum_sub_total ?? 0,
                    ];
                })
                ->sortByDesc('total_revenue')
                ->values();
                // Fetch best POS sessions with aggregated revenue and order count
                $this->bestPosSessions = PosSession::isCompany(current_company()->id)
                    ->when($this->restaurant, function ($query) {
                        $query->where('pos_id', $this->restaurant);
                    })
                    ->withCount(['orders as orders_count' => function ($subQuery) {
                        $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }])
                    ->withSum(['orders as orders_sum_total_amount' => function ($subQuery) {
                        $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    }], 'total_amount')
                    ->get()
                    ->map(function ($session) {
                        return [
                            'name' => $session->name,
                            'total_orders' => $session->orders_count ?? 0,
                            'total_revenue' => $session->orders_sum_total_amount ?? 0,
                        ];
                    })
                    ->sortByDesc('total_revenue')
                    ->values();


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

    public function getMonthlyOrders(): \Illuminate\Support\Collection
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $orders = PosOrder::isCompany(current_company()->id)
            ->when($this->restaurant, function ($query) {
                $query->where('pos_id', $this->restaurant);
            })
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(total_amount) as total_revenue, SUM(total_amount - paid_amount) as total_unpaid')
            ->groupBy('year', 'month')
            ->orderByRaw('year ASC, month ASC')
            ->get();

        return $orders->map(fn ($invoice) => [
            'month'   => Carbon::create($invoice->year, $invoice->month, 1)->format('F Y'),
            'revenue' => round((float) $invoice->total_revenue, 2),
            'unpaid'  => round((float) $invoice->total_unpaid, 2),
        ]);
    }

    public function updatedProperty($property){
        $this->loadData($this->property);
    }


    public function export(ReportExportService $exportService)
    {

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Invoiced' => ['value' => format_currency($this->soldAmount), 'change' => format_currency($this->unpaidAmount)],
            'Average Invoice' => ['value' => format_currency($this->averageOrderAmount), 'change' => $this->numberOfOrders],
            'Days Sales Outstanding (DSO)' => ['value' => $this->dso, 'change' => "0%"],
        ];

        $topInvoices = $this->orders->map(function ($invoice) {
            return [
                'reference' => $invoice->reference,
                'guest' => $invoice->guest->name,
                'agent' => $invoice->agent->name,
                'status' => $this->getPaymentStatus($invoice->status),
                'date' => Carbon::parse($invoice->date)->format('m/d/y'),
                'revenue' => format_currency($invoice->total_amount)
            ];
        })
        ->sortByDesc('revenue');

        $topPayments = $this->payments->map(function ($payment) {
            return [
                'reference' => $payment->reference,
                'invoice' => $payment->invoice->reference,
                'date' => Carbon::parse($payment->date)->format('m/d/y'),
                // 'status' => $this->getPaymentStatus($payment->status),
                'amount' => format_currency($payment->amount)
            ];
        })
        ->sortByDesc('amount');

        // Assign to detailed sections
        $detailedSections = [
            'Top Invoices' => $topInvoices,
            'Top Payments' => $topPayments,
        ];

        // ✅ Export Report
        return $exportService->export('Invoicing Report', $summaryData, $detailedSections, 'xlsx');
    }

    public function getPaymentStatus($status)
    {
        if ($status == 'partial') {
            return 'Partially Paid';
        } elseif ($status == 'pending') {
            return 'Not Paid';
        } elseif ($status == 'paid') {
            return 'Paid';
        }

        return 'Unknown';
    }

    public function render()
    {
        return view('pos::livewire.dashboards.order');
    }
}
