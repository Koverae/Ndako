<?php

namespace Modules\Properties\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
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
    public $period = 1, $property;
    public $occupancyRate, $occupiedNights = 0, $occupiedRooms = 0, $totalNightsAvailable = 0, $revPar = 0, $adr;
    public $bestSellingRooms, $bestSellingRoomTypes;
    public $properties, $propertyTypes, $monthlyOccupancyRates, $revenueByType;

    public function mount(){
        $this->properties = PropertyProperty::isCompany(current_company()->id)->get();
        $this->propertyTypes = PropertyType::isCompany(current_company()->id)->get();

        $this->property = current_property()->id;
        $this->loadData();

        $this->fetchRevenueByType();
    }

    public function loadData($property = null){
        if($property){
            $this->property = $property;
        }

        $propertyId = $this->property; // Property filter (nullable)

        // Define the date range (e.g., last 7 days)
        $startDate = Carbon::now()->subDays($this->period ?? 7)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $currentStart = Carbon::now()->subDays($this->period);
        $previousStart = Carbon::now()->subDays($this->period * 2);
        $now = Carbon::now();

        // Total number of rooms in the property (or all properties if $propertyId is null)
        $totalRooms = PropertyUnit::isCompany(current_company()->id)
            ->when($propertyId, function ($query) use ($propertyId) {
                $query->where('property_id', $propertyId);
            })
            ->get();

        $currentRooms = PropertyUnit::isCompany(current_company()->id)
            ->when($this->property, function ($query) {
                $query->where('property_id', $this->property); // Apply filter if $property is set
            })
            ->with(['bookings' => function ($query) {
                $query->whereIn('status', ['confirmed', 'completed'])
                ->select('id', 'property_unit_id', 'total_amount',
                    DB::raw('DATEDIFF(check_out, check_in) as nights')
                )
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

        // Total nights available for the given period
        $this->totalNightsAvailable = $totalRooms->whereIn('status', ['vacant'])->count() * max($this->period, 1);
        // $this->totalNightsAvailable = round($totalRooms * $startDate->diffInDays($endDate));

        // Calculate total occupied nights
        $this->occupiedNights = $currentRooms->sum('total_nights');

        $this->occupiedRooms = Booking::isCompany(current_company()->id)
        ->whereIn('status', ['confirmed', 'completed'])
        ->whereHas('unit', function ($query) {
            $query->whereIn('status', ['occupied', 'expected-arrival', 'reserved']);
        })
        ->when($this->property, function ($query) {
            $query->whereHas('unit', fn($q) => $q->where('property_id', $this->property));
        })
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in', [$startDate, $endDate])
                  ->orWhereBetween('check_out', [$startDate, $endDate])
                  ->orWhere(function ($subQuery) use ($startDate, $endDate) {
                      $subQuery->where('check_in', '<=', $startDate)
                               ->where('check_out', '>=', $endDate);
                  });
        })
        ->selectRaw('SUM(DATEDIFF(LEAST(check_out, ?), GREATEST(check_in, ?))) as occupied_nights', [$endDate, $startDate])
        ->value('occupied_nights') ?? 0;

        // Calculate occupancy rate
        $this->occupancyRate = ($this->totalNightsAvailable > 0)
            ? round(($this->occupiedRooms / $this->totalNightsAvailable) * 100, 2)
            : 0;

        // Fetch total revenue for the period
        $totalRevenue = Booking::isCompany(current_company()->id)
            ->whereIn('status', ['confirmed', 'completed'])
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
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['unitType', 'bookings' => function ($query) use ($startDate, $endDate) {
            $query->select(
                'id',
                'property_unit_id',
                DB::raw("SUM(CASE WHEN status IN ('canceled') THEN DATEDIFF(check_out, check_in) ELSE 0 END) as nights_canceled"),
                DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN DATEDIFF(check_out, check_in) ELSE 0 END) as nights_sold"),
                DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN total_amount ELSE 0 END) as revenue")
            )
            // Apply date range filter (period)
            ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
            ->groupBy('property_unit_id', 'id');
        }])
        ->get()
        ->map(function ($room) {

            $startDate = Carbon::now()->subDays($this->period ?? 7)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $totalAvailableNights = $startDate->diffInDays($endDate);
            // $totalAvailableNights = $startDate->diffInDays($endDate) * $room->unitType->capacity;

            $totalNightsSold = $room->bookings->sum('nights_sold');
            $totalNightsLost = $room->bookings->sum('nights_canceled');
            $occupancyRate = $totalAvailableNights > 0
                ? round(($totalNightsSold / $totalAvailableNights) * 100, 2)
                : 0;

            return [
                'room' => $room->name,
                'room_type' => $room->unitType->name ?? 'N/A',
                'nights_sold' => $totalNightsSold,
                'nights_canceled' => $totalNightsLost,
                'occupancy_rate' => $occupancyRate . '%',
                'revenue' => format_currency($room->bookings->sum('revenue')),
            ];
        })
        ->sortByDesc('revenue'); // Sort by revenue

        // Fetch Best Selling Room Types on the specified period
        $this->bestSellingRoomTypes = PropertyUnitType::isCompany(current_company()->id)
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['units.bookings' => function ($query) {
            $query->select(
                    'id',
                    'property_unit_id',
                    // 'property_unit_type_id',
                    DB::raw("SUM(CASE WHEN status IN ('canceled') THEN DATEDIFF(check_out, check_in) ELSE 0 END) as nights_canceled"),
                    DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN DATEDIFF(check_out, check_in) ELSE 0 END) as nights_sold"),
                    DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN total_amount ELSE 0 END) as revenue")
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

            // Sum nights canceled for each room type
            $totalNightsCanceled = $roomType->units->flatMap(function ($unit) {
                return $unit->bookings;
            })->sum('nights_canceled');

            // Sum revenue for each room type
            $totalRevenue = $roomType->units->flatMap(function ($unit) {
                return $unit->bookings;
            })->sum('revenue');

            return [
                'room_type' => $roomType->name,
                'nights_sold' => $totalNightsSold,
                'nights_canceled' => $totalNightsCanceled,
                'revenue' => format_currency($totalRevenue),
            ];
        })
        ->sortByDesc('revenue'); // Sort by revenue descending
    }

    public function updatedPeriod(){
        $this->loadData();
    }

    public function updatedProperty(){
        $this->loadData($this->property);
        $this->fetchRevenueByType();
    }

    public function fetchRevenueByType()
    {
        $startDate = Carbon::now()->subDays($this->period);
        $endDate = Carbon::now();

        $this->revenueByType = PropertyUnitType::isCompany(current_company()->id)
            ->when($this->property, function ($query) {
                $query->where('property_id', $this->property); // Apply filter if $property is set
            })
            ->with(['units.bookings' => function ($query) use ($startDate, $endDate) {
                $query->select(
                'property_unit_id', // Keep only necessary columns in SELECT
                DB::raw("SUM(CASE WHEN status IN ('canceled') THEN DATEDIFF(LEAST(check_out, '$endDate'), GREATEST(check_in, '$startDate')) ELSE 0 END) as nights_canceled"),
                DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN DATEDIFF(LEAST(check_out, '$endDate'), GREATEST(check_in, '$startDate')) ELSE 0 END) as nights_sold"),
                DB::raw("SUM(CASE WHEN status IN ('confirmed', 'completed') THEN total_amount ELSE 0 END) as revenue")
            )
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('check_in', [$startDate, $endDate])
                    ->orWhereBetween('check_out', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query->where('check_in', '<', $startDate)
                                ->where('check_out', '>', $endDate);
                    });
            })
            ->groupBy('property_unit_id'); // Ensure only aggregated columns are used
            }])
            ->get()
            ->map(function ($roomType) {
                $totalRevenue = $roomType->units->flatMap(fn($unit) => $unit->bookings)->sum('revenue');

                return [
                    'label' => $roomType->name, // Room type name
                    'value' => $totalRevenue,   // Revenue
                ];
            })
            ->filter(fn($roomType) => $roomType['value'] > 0) // Exclude room types with no revenue
            ->values(); // Reset array keys
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


    public function export(ReportExportService $exportService)
    {
        $property = PropertyProperty::find($this->property);

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Occupancy Rate' => ['value' => "{$this->occupancyRate}%", 'change' => 0],
            'Average Daily Rate (ADR)' => ['value' => format_currency($this->adr), 'change' => 0],
            'Revenue Per Available Room (RevPAR)' => ['value' => format_currency($this->revPar), 'change' => "0%"],
            'Room Nights Sold' => ['value' => $this->occupiedNights, 'change' => "0%"],
            'Occupied Rooms' => ['value' => $this->occupiedRooms, 'change' => "0%"],
            'Room Nights Available' => ['value' => $this->totalNightsAvailable, 'change' => "0%"],
        ];


        // Assign to detailed sections
        $detailedSections = [
            'Best Selling Rooms' => $this->bestSellingRooms,
            'Best Selling Room Types' => $this->bestSellingRoomTypes,
        ];

        // ✅ Export Report
        return $exportService->export("{$property->name} Report", $summaryData, $detailedSections, 'xlsx');
    }
}
