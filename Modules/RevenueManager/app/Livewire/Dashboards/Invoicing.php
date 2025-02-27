<?php

namespace Modules\RevenueManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use Illuminate\Support\Facades\DB;
use Modules\ChannelManager\Models\Booking\BookingPayment;

class Invoicing extends Component
{
    public $period = 7, $property;
    public $invoicedAmount, $unpaidAmount, $averageInvoiceAmount, $numberOfInvoices, $dso, $invoices, $payments;
    public $properties, $units, $unitTypes, $mothlyInvoices;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->units = PropertyUnit::isCompany(current_company()->id)->get();
        $this->unitTypes = PropertyUnitType::isCompany(current_company()->id)->get();

        $this->property = $this->properties->first()->id ?? null;
        $this->loadData();
    }

    public function loadData(){

        $invoices = BookingInvoice::isCompany(current_company()->id)
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
        ->when($this->property, function ($query) {
            $query->with('booking', function ($query) {
                $query->where('property_unit_id', $this->property);
            });
        })
        ->select(
            DB::raw('SUM(total_amount) as total_invoiced'),
            DB::raw('SUM(total_amount - paid_amount) as total_unpaid')
        )
        ->first();

        $this->invoicedAmount = $invoices->total_invoiced ?? 0;
        $this->unpaidAmount = $invoices->total_unpaid ?? 0;

        $invoiceStats = BookingInvoice::isCompany(current_company()->id)
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
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
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
        ->when($this->property, function ($query) {
            $query->with('booking', function ($query) {
                $query->where('property_unit_id', $this->property);
            });
        })
        ->orderByDesc('total_amount')
        ->get();

        $this->payments = BookingPayment::isCompany(current_company()->id)
        ->whereBetween('date', [Carbon::now()->subDays($this->period), Carbon::now()])
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
    public function getMonthlyInvoices()
    {
        // Fetch the monthly bookings data for the current year
        $invoices = BookingInvoice::whereBetween('date', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(total_amount) as revenue, SUM(total_amount - paid_amount) as unpaid')
            ->groupBy('month', 'year')
            ->orderByRaw('YEAR(date) ASC, MONTH(date) ASC') // Sort by year and month in ascending order
            ->get();

        return $invoices->map(function ($invoice) {
            return [
                'month' => Carbon::create($invoice->year, $invoice->month, 1)->format('F Y'),
                'revenue' => $invoice->revenue,
                'unpaid' => $invoice->unpaid,
            ];
        });
    }

    public function updatedProperty($property){
        $this->property = $property;
        $this->mount();
    }

    public function render()
    {
        return view('revenuemanager::livewire.dashboards.invoicing');
    }
}
