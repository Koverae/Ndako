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
use Modules\ChannelManager\Models\Booking\BookingPayment;

class Invoicing extends Component
{
    public $period = 1, $property;
    public $invoicedAmount, $unpaidAmount, $averageInvoiceAmount, $numberOfInvoices, $dso, $invoices, $payments;
    public $properties, $units, $unitTypes, $mothlyInvoices;
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

        $this->property = $property;
        $this->units = PropertyUnit::isCompany(current_company()->id)->isProperty($this->property)->get();
        $this->unitTypes = PropertyUnitType::isCompany(current_company()->id)->isProperty($this->property)->get();

        $invoices = BookingInvoice::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->with(['booking' => function ($query) {
                $query->with(['unit' => function ($subQuery) {
                    $subQuery->when($this->property, function ($property){
                        $property->where('property_id', $this->property);
                    });
                }]);
        }])
        ->select(
            DB::raw('SUM(total_amount) as total_invoiced'),
            DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $this->invoicedAmount = $invoices->total_invoiced ?? 0;
        $this->unpaidAmount = $invoices->total_unpaid ?? 0;

        $invoiceStats = BookingInvoice::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->with(['booking' => function ($query) {
                $query->with(['unit' => function ($subQuery) {
                    $subQuery->when($this->property, function ($property){
                        $property->where('property_id', $this->property);
                    });
                }]);
        }])
        ->select(
            DB::raw('AVG(total_amount) as average_invoice_amount'),
            DB::raw('COUNT(id) as number_of_invoices')
        )
        ->first();

        $this->averageInvoiceAmount = round($invoiceStats->average_invoice_amount) ?? 0;
        $this->numberOfInvoices = $invoiceStats->number_of_invoices ?? 0;


        // Number of days for the period (e.g., last 30 days)
        $daysInPeriod = 365; // Change as necessary (e.g., 7, 30, 365)

        // Calculate DSO
        if ($this->invoicedAmount > 0) {
            $this->dso = round(($this->unpaidAmount / $this->invoicedAmount) * $daysInPeriod);
        } else {
            $this->dso = 0; // Avoid division by zero
        }

        $this->invoices = BookingInvoice::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->with(['booking' => function ($query) {
                $query->whereHas('unit', function ($query) {
                    $query->when($this->property, fn($q, $id) => $q->isProperty($id));
                });
        }])
        ->orderByDesc('total_amount')
        ->get();

        $this->payments = BookingPayment::isCompany(current_company()->id)
        ->whereBetween('date', [$this->startDate, $this->endDate])
        ->when($this->property, function ($query) {
            $query->with('invoice.booking', function ($query) {
                $query->where('property_unit_id', $this->property);
            });
        })
        ->orderByDesc('amount')
        ->get();

        $this->mothlyInvoices = $this->getMonthlyInvoices();

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
    
    public function getMonthlyInvoices(): \Illuminate\Support\Collection
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $invoices = BookingInvoice::with(['booking' => function ($query) {
                $query->with(['unit' => function ($subQuery) {
                    $subQuery->when($this->property, function ($property){
                        $property->where('property_id', $this->property);
                    });
                }]);
            }])
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(total_amount) as total_revenue, SUM(total_amount - paid_amount) as total_unpaid')
            ->groupBy('year', 'month')
            ->orderByRaw('year ASC, month ASC')
            ->get();

        return $invoices->map(fn ($invoice) => [
            'month'   => Carbon::create($invoice->year, $invoice->month, 1)->format('F Y'),
            'revenue' => round((float) $invoice->total_revenue, 2),
            'unpaid'  => round((float) $invoice->total_unpaid, 2),
        ]);
    }

    public function updatedProperty($property){
        $this->loadData($this->property);
    }

    public function render()
    {
        return view('revenuemanager::livewire.dashboards.invoicing');
    }


    public function export(ReportExportService $exportService)
    {

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Invoiced' => ['value' => format_currency($this->invoicedAmount), 'change' => format_currency($this->unpaidAmount)],
            'Average Invoice' => ['value' => format_currency($this->averageInvoiceAmount), 'change' => $this->numberOfInvoices],
            'Days Sales Outstanding (DSO)' => ['value' => $this->dso, 'change' => "0%"],
        ];

        $topInvoices = $this->invoices->map(function ($invoice) {
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
}
