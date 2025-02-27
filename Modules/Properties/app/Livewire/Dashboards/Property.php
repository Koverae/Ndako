<?php

namespace Modules\Properties\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Properties\Models\Property\Property as PropertyProperty;
use Modules\Properties\Models\Property\PropertyType;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Properties\Models\Property\PropertyUnitType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Property extends Component
{
    public $period = 7, $property;
    public $occupancyRate, $occupiedNights = 0, $totalNightsAvailable = 0, $revPar = 0, $adr;
    public $bestSellingRooms, $bestSellingRoomTypes;
    public $properties, $propertyTypes, $monthlyOccupancyRates, $revenueByType;

    public function mount(){
        $this->properties = PropertyProperty::isCompany(current_company()->id)->get();
        $this->propertyTypes = PropertyType::isCompany(current_company()->id)->get();

        $this->property = $this->properties->first()->id ?? null;

        $this->loadData();
    }

    public function loadData(){

        $propertyId = $this->property ?? null; // Property filter (nullable)

        // Define the date range (e.g., last 7 days)
        $startDate = Carbon::now()->subDays($this->period ?? 7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Total number of rooms in the property (or all properties if $propertyId is null)
        $totalRooms = PropertyUnit::isCompany(current_company()->id)
            ->when($propertyId, function ($query) use ($propertyId) {
                $query->where('property_id', $propertyId);
            })
            ->count();

        // Total nights available for the given period
        $this->totalNightsAvailable = round($totalRooms * $startDate->diffInDays($endDate));

        // Calculate total occupied nights
        $this->occupiedNights = Booking::isCompany(current_company()->id)
            ->whereHas('unit', function ($query) use ($propertyId) {
                if ($propertyId) {
                    $query->where('property_id', $propertyId);
                }
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                        $subQuery->where('check_in', '<=', $startDate)
                                 ->where('check_out', '>=', $endDate);
                    });
            })
            ->get()
            ->sum(function ($booking) use ($startDate, $endDate) {
                $checkIn = Carbon::parse($booking->check_in);
                $checkOut = Carbon::parse($booking->check_out);

                // Ensure we only count nights within the period
                $effectiveStart = max($checkIn, $startDate);
                $effectiveEnd = min($checkOut, $endDate);

                return $effectiveEnd->greaterThan($effectiveStart)
                    ? round($effectiveStart->diffInDays($effectiveEnd))
                    : 0; // Prevent negative days
            });

        // Calculate occupancy rate
        $this->occupancyRate = ($this->totalNightsAvailable > 0)
            ? round(($this->occupiedNights / $this->totalNightsAvailable) * 100, 2)
            : 0;

        // Fetch total revenue for the period
        $totalRevenue = Booking::isCompany(current_company()->id)
            ->whereBetween('check_in', [$startDate, $endDate])
            ->sum('total_amount');

        // Calculate RevPAR
        $this->revPar = $this->totalNightsAvailable > 0 ? round($totalRevenue / $this->totalNightsAvailable) : 0;

        // Fetch total revenue and total booked nights for the period
        $bookingStats = Booking::isCompany(current_company()->id)
            ->whereBetween('check_in', [$startDate, $endDate])
            ->select(
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('SUM(DATEDIFF(check_out, check_in)) as total_nights')
            )
            ->first();

        $totalRevenue = $bookingStats->total_revenue ?? 0;
        $totalNights = $bookingStats->total_nights ?? 0;

        // Calculate ADR
        $this->adr = $totalNights > 0 ? round($totalRevenue / $totalNights, 2) : 0;

        // Fetch Best Selling Rooms
        $this->bestSellingRooms = PropertyUnit::isCompany(current_company()->id)
        ->with(['unitType', 'bookings' => function ($query) {
            $query->select(
                'id',
                'property_unit_id',
                DB::raw('SUM(DATEDIFF(check_out, check_in)) as nights_sold'),
                DB::raw('SUM(total_amount) as revenue')
            )
            // Apply date range filter (period)
            ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
            ->groupBy('property_unit_id', 'id');
        }])
        ->get()
        ->map(function ($room) {

            $startDate = Carbon::now()->subDays($this->period ?? 7)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $totalAvailableNights = $startDate->diffInDays($endDate) * $room->unitType->capacity;

            $totalNightsSold = $room->bookings->sum('nights_sold');
            $occupancyRate = $totalAvailableNights > 0
                ? round(($totalNightsSold / $totalAvailableNights) * 100, 2)
                : 0;

            return [
                'room' => $room->name,
                'room_type' => $room->unitType->name ?? 'N/A',
                'nights_sold' => $totalNightsSold,
                'occupancy_rate' => $occupancyRate . '%',
                'revenue' => format_currency($room->bookings->sum('revenue')),
            ];
        })
        ->sortByDesc('revenue'); // Sort by revenue

        // Fetch Best Selling Room Types on the specified period
        $this->bestSellingRoomTypes = PropertyUnitType::isCompany(current_company()->id)
        ->with(['units.bookings' => function ($query) {
            $query->select(
                    'id',
                    'property_unit_id',
                    // 'property_unit_type_id',
                    DB::raw('DATEDIFF(check_out, check_in) as nights_sold'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                // Apply date range filter (period)
                ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
                ->orWhereBetween('check_out', [Carbon::now()->subDays($this->period), Carbon::now()])
                ->groupBy('id');
        }])
        ->get()
        ->map(function ($roomType) {
            // Sum nights sold for each room type
            $totalNightsSold = $roomType->units->flatMap(function ($unit) {
                return $unit->bookings;
            })->sum('nights_sold');

            // Sum revenue for each room type
            $totalRevenue = $roomType->units->flatMap(function ($unit) {
                return $unit->bookings;
            })->sum('revenue');

            return [
                'room_type' => $roomType->name,
                'nights_sold' => $totalNightsSold,
                'revenue' => format_currency($totalRevenue),
            ];
        })
        ->sortByDesc('revenue'); // Sort by revenue descending

        $this->revenueByType = PropertyUnitType::isCompany(current_company()->id)
        ->with(['units.bookings' => function ($query) {
            $query->select(
                    'id',
                    'property_unit_id',
                    DB::raw('DATEDIFF(check_out, check_in) as nights_sold'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
                ->orWhereBetween('check_out', [Carbon::now()->subDays($this->period), Carbon::now()])
                ->groupBy('id');
        }])
        ->get()
        ->map(function ($roomType) {
            $totalRevenue = $roomType->units->flatMap(function ($unit) {
                return $unit->bookings;
            })->sum('revenue');

            return [
                'label' => $roomType->name, // Room type name
                'value' => $totalRevenue,   // Revenue
            ];
        })
        ->filter(fn($roomType) => $roomType['value'] > 0) // Exclude room types with no revenue
        ->values(); // Reset array keys
    }

    public function updatedPeriod(){
        $this->loadData();
    }

    public function render()
    {
        return view('properties::livewire.dashboards.property', [
            'roomTypeChartData' => [
                'labels' => $this->revenueByType->pluck('label')->toArray(),
                'series' => $this->revenueByType->pluck('value')->toArray(),
            ]
        ]);
    }

    public function exportData()
    {
        // Fetch invoices from the database
        // $invoices = BookingInvoice::with(['guest', 'agent'])
        //     ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
        //     ->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set data headers
            $sheet->setCellValue('A1', 'Occupancy Rate');
            $sheet->setCellValue('B1', 'ADR');
            $sheet->setCellValue('C1', 'RevPAR');
            $sheet->setCellValue('D1', 'Room Nights Sold');
            $sheet->setCellValue('E1', 'Room Nights Available');

            // Set the data
            $sheet->setCellValue('A2', $this->occupancyRate . '%');
            $sheet->setCellValue('B2', format_currency($this->adr));
            $sheet->setCellValue('C2', format_currency($this->revPar));
            $sheet->setCellValue('D2', $this->occupiedNights);
            $sheet->setCellValue('E2', $this->totalNightsAvailable);

            // Apply some styling (optional)
            $sheet->getStyle('A1:E1')->getFont()->setBold(true);
            $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1:E1')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(20);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(20);

            // Create a writer and output the file
            $writer = new Xlsx($spreadsheet);
            $fileName = 'dashboard.xlsx';

            // Output to browser
            return response()->stream(
                function () use ($writer) {
                    $writer->save('php://output');
                },
                200,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="dashboard.xlsx"',
                ]
            );

    }
}
