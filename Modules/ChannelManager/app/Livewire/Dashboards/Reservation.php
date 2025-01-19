<?php

namespace Modules\ChannelManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Models\Booking\BookingInvoice;
use Modules\ChannelManager\Models\Guest\Guest;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Reservation extends Component
{

    public $period = 7, $property, $type, $room, $guest, $source;
    public $bookings, $bookingGrowth = 0, $revenue = 0, $revenueGrowth = 0, $avgRevenue = 0, $avgRevenueGrowth = 0, $cancellationRate = 0;
    public $rooms, $guestBooks, $roomTypes, $monthlyBookings;
    public $properties, $units, $unitTypes = [], $guests = [];

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->units = PropertyUnit::isCompany(current_company()->id)->get();
        $this->unitTypes = PropertyUnitType::isCompany(current_company()->id)->get();
        $this->guests = Guest::isCompany(current_company()->id)->get();

        $this->monthlyBookings = $this->getMonthlyBookings();

        $this->property = $this->properties->first()->id;
        $this->loadData();

    }
    public function getMonthlyBookings()
    {
        // Fetch the monthly bookings data for the current year
        $bookings = Booking::whereBetween('check_in', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->selectRaw('MONTH(check_in) as month, YEAR(check_in) as year, SUM(total_amount) as revenue')
            ->groupBy('month', 'year')
            ->orderByRaw('YEAR(check_in) ASC, MONTH(check_in) ASC') // Sort by year and month in ascending order
            ->get();

        return $bookings->map(function ($booking) {
            return [
                'month' => Carbon::create($booking->year, $booking->month, 1)->format('F Y'),
                'revenue' => $booking->revenue,
            ];
        });
    }

    public function loadData(){

        $this->bookings = Booking::isCompany(current_company()->id)
            ->where('status', 'confirmed') // Assuming 'status' column exists
            ->orderByDesc('total_amount')
            ->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()])
            ->get();
        $this->revenue = $this->bookings->sum('total_amount');
        $this->avgRevenue = $this->bookings->avg('total_amount');

        // Canceled bookings count
        $canceledBookings = $this->bookings
        ->where('status', 'canceled') // Assuming 'status' column exists
        ->count();

        // Calculate the cancellation rate (avoiding division by zero)
        $this->cancellationRate = $this->bookings->count() > 0
        ? ($canceledBookings / $this->bookings->count()) * 100
        : 0;

        $this->rooms = PropertyUnit::isCompany(current_company()->id)
            ->withCount(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }]) // Adds bookings_count for the last 7 days
            ->withSum(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }], 'total_amount') // Adds bookings_sum_total_amount for the last 7 days
            ->orderByDesc('bookings_sum_total_amount') // Sort by total revenue
            ->get();

        $this->guestBooks = Guest::isCompany(current_company()->id)
            ->withCount(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }]) // Adds bookings_count for the last 7 days
            ->withSum(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }], 'total_amount') // Adds bookings_sum_total_amount for the last 7 days
            ->orderByDesc('bookings_sum_total_amount') // Sort by total revenue
            ->get();

        // Fetch room types with aggregated booking revenue
        $this->roomTypes = PropertyUnitType::isCompany(current_company()->id)
        ->with(['units' => function ($query) {
            $query->with(['bookings' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }]) // Include only bookings from the last 7 days
            ->withCount(['bookings' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }]) // Count bookings for the last 7 days
            ->withSum(['bookings' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }], 'total_amount'); // Sum total amount for the last 7 days
        }])
        ->get()
        ->map(function ($type) {
            $totalRevenue = $type->units->sum('bookings_sum_total_amount') ?? 0;
            $totalBookings = $type->units->sum('bookings_count');

            return [
                'name' => $type->name,
                'total_revenue' => $totalRevenue,
                'total_bookings' => $totalBookings,
            ];
        })
        ->sortByDesc('total_revenue'); // Sort by revenue descending
    }

    public function updatedPeriod($property){
        $this->mount();
    }

    public function render()
    {
        return view('channelmanager::livewire.dashboards.reservation');
    }

    public function exportData()
    {
        // Fetch invoices from the database
        $invoices = BookingInvoice::with(['guest', 'agent'])
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->get();

            // Initialize PhpSpreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Add headers
            $headers = ['Reference', 'Guest', 'Status', 'Agent', 'Date', 'Revenue'];
            $sheet->fromArray($headers, NULL, 'A1');

            // Style headers
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1:F1')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);

            // Add rows
            $row = 2; // Start from the second row
            foreach ($invoices as $invoice) {
                $sheet->setCellValue('A' . $row, $invoice->reference);
                $sheet->setCellValue('B' . $row, $invoice->guest->name);
                $sheet->setCellValue('C' . $row, $this->getPaymentStatus($invoice->payment_status));
                $sheet->setCellValue('D' . $row, $invoice->agent->name);
                $sheet->setCellValue('E' . $row, Carbon::parse($invoice->date)->format('m/d/y'));
                $sheet->setCellValue('F' . $row, format_currency($invoice->total_amount));
                $row++;
            }

            // Set column widths for better display
            foreach (range('A', 'F') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Output to Excel format
            $writer = new Xlsx($spreadsheet);

            // Stream the file for download
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="invoices.xlsx"',
            ];

            return response()->stream(function() use ($writer) {
                $writer->save('php://output');
            }, 200, $headers);

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
