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

    public $period = 7, $property, $type, $room, $guest, $source = 'direct-booking';
    public $bookings, $canceledBookings, $bookingGrowth = 0, $revenue = 0, $revenueGrowth = 0, $avgRevenue = 0, $avgRevenueGrowth = 0, $cancellationRate = 0;
    public $cancellationRateChange = 0, $bookingRateChange = 0, $revenueChange = 0, $averageRevenueChange = 0;
    public $rooms, $guestBooks, $roomTypes, $monthlyBookings;
    public $properties, $units, $unitTypes = [], $guests = [];

    public function mount($updating = false){
        
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = $this->properties->first()->id ?? null;
        $this->units = PropertyUnit::isCompany(current_company()->id)->get();
        $this->unitTypes = PropertyUnitType::isCompany(current_company()->id)->get();
        $this->guests = Guest::isCompany(current_company()->id)->get();

        $this->monthlyBookings = $this->getMonthlyBookings();

        $this->loadData();

    }
    public function getMonthlyBookings()
    {
        // Fetch monthly revenue data (confirmed + canceled) for the current year
        $bookings = Booking::with(['unit' => function ($subQuery) {
                $subQuery->when($this->property, function ($query) {
                    $query->where('property_id', $this->property); // Apply filter if $property is set
                });
            }])
            ->whereYear('check_in', Carbon::now()->year)
            ->selectRaw('
                MONTH(check_in) as month,
                YEAR(check_in) as year,
                SUM(CASE WHEN status IN ("confirmed", "completed") THEN total_amount ELSE 0 END) as revenue,
                SUM(CASE WHEN status = "canceled" THEN total_amount ELSE 0 END) as canceled_revenue
            ')
            ->groupBy('month', 'year')
            ->orderByRaw('YEAR(check_in) ASC, MONTH(check_in) ASC')
            ->get();

        // Transform results for output
        return $bookings->map(fn ($booking) => [
            'month'   => Carbon::create($booking->year, $booking->month, 1)->format('F Y'),
            'revenue' => round($booking->revenue, 2),
            'cancel'  => round($booking->canceled_revenue, 2),
        ]);
    }

    public function loadData(){

        $currentStart = Carbon::now()->subDays($this->period);
        $previousStart = Carbon::now()->subDays($this->period * 2);
        $now = Carbon::now();

        // Fetch both current and previous period bookings
        $currentBookings = Booking::isCompany(current_company()->id)
            // ->where('status', 'confirmed') // Assuming 'status' column exists

            ->orderByDesc('total_amount')
            ->whereBetween('created_at', [$currentStart, $now])
            ->get();

        $previousBookings = Booking::isCompany(current_company()->id)
            ->where('source', $this->source)
            ->with(['unit' => function ($query) {
                $query->when($this->property, fn ($q, $id) => $q->isProperty($id))
                    ->with(['unitType' => fn ($subQuery) =>
                        $subQuery->when($this->type, fn ($q, $type) => $q->isType($type))
                    ]);
            }])
            // ->where('status', 'confirmed') // Assuming 'status' column exists
            ->orderByDesc('total_amount')
            ->whereBetween('created_at', [$previousStart, $currentStart])
            ->get();

        // Get the total
        $currentTotal = $currentBookings->count();
        $previousTotal = $previousBookings->count();

        // Get current & previous confirmed bookings
        $confirmedCurrentBookings = $currentBookings->whereIn('status', ['confirmed', 'completed']);
        $confirmedPreviousBookings = $previousBookings->whereIn('status', ['confirmed', 'completed']);

        // Assign values
        $this->bookings = $confirmedCurrentBookings;


        // Calculate booking growth rate
        if ($confirmedPreviousBookings->count() > 0) {
            // Normal percentage change formula
            $this->bookingRateChange = round((($confirmedCurrentBookings->count() - $confirmedPreviousBookings->count()) / $confirmedPreviousBookings->count()) * 100, 1);
        } else {
            // If there were no bookings in the previous period, take the current number as the growth percentage
            $this->bookingRateChange = $confirmedCurrentBookings->count() > 0 ? $confirmedCurrentBookings->count() : 0;
        }

        $this->revenue = $confirmedCurrentBookings->sum('total_amount');

        // Get total revenue for the current period
        $currentRevenue = $confirmedCurrentBookings->sum('total_amount');
        // Get total revenue for the previous period
        $previousRevenue = $confirmedPreviousBookings->sum('total_amount');

        // Calculate revenue change percentage
        if ($previousRevenue > 0) {
            $this->revenueChange = round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1);
        } else {
            $this->revenueChange = $currentRevenue > 0 ? 100 : 0;
        }

        $this->avgRevenue = $confirmedCurrentBookings->avg('total_amount');

        // Get total revenue for the current period
        $currentAvg = $confirmedCurrentBookings->avg('total_amount');
        // Get total revenue for the previous period
        $previousAvg = $confirmedPreviousBookings->avg('total_amount');

        // Calculate revenue change percentage
        if ($previousAvg > 0) {
            $this->averageRevenueChange = round((($currentAvg - $previousAvg) / $previousAvg) * 100, 1);
        } else {
            $this->averageRevenueChange = $currentAvg > 0 ? 100 : 0;
        }

        // Get current & previous canceled bookings
        $currentCanceledBookings = $currentBookings->where('status', 'canceled');
        $previousCanceledBookings = $previousBookings->where('status', 'canceled');

        // Calculate cancellation rates & canceled bookings
        $this->canceledBookings = $currentCanceledBookings;
        $this->cancellationRate = $currentTotal > 0 ? round(($currentCanceledBookings->count() / $currentTotal) * 100, 1) : 0;
        $previousRate = $previousTotal > 0 ? ($previousCanceledBookings->count() / $previousTotal) * 100 : 0;

        if ($previousRate > 0) {
            // Standard percentage change formula
            $this->cancellationRateChange = round((($this->cancellationRate - $previousRate) / $previousRate) * 100, 1);
        } else {
            // If there were no cancellations in the previous period, but there are now
            $this->cancellationRateChange = $this->cancellationRate > 0 ? $this->cancellationRate : 0;
        }

        $this->rooms = PropertyUnit::isCompany(current_company()->id)
            ->when($this->property, function ($query) {
                $query->where('property_id', $this->property); // Apply filter if $property is set
            })
            ->withCount(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }]) // Adds bookings_count for the last 7 days
            ->withSum(['bookings' => function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays($this->period), Carbon::now()]);
            }], 'total_amount') // Adds bookings_sum_total_amount for the last 7 days
            ->orderByDesc('bookings_sum_total_amount') // Sort by total revenue
            ->get();

        $this->guestBooks = Guest::isCompany(current_company()->id)
            ->with(['bookings' => function($query) {
                $query->with(['unit' => function ($subQuery) {
                    $subQuery->when($this->property, function ($query) {
                        $query->where('property_id', $this->property); // Apply filter if $property is set
                    });
                }]);
            }])
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
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
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
        $this->mount(true);
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
