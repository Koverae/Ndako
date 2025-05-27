<?php

namespace Modules\ChannelManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\App\Services\ReportExportService;
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

    public $period = 1, $property, $type, $room, $guest, $source = 'direct-booking';
    public $bookings, $canceledBookings, $bookingGrowth = 0, $revenue = 0, $revenueGrowth = 0, $avgRevenue = 0, $avgRevenueGrowth = 0, $cancellationRate = 0;
    public $cancellationRateChange = 0, $bookingRateChange = 0, $revenueChange = 0, $averageRevenueChange = 0;
    public $rooms, $guestBooks, $roomTypes, $monthlyBookings;
    public $properties, $units, $unitTypes = [], $guests = [];
    // Report
    public $reportType = 'bookings', $format = 'xlsx';
    public $startDate, $endDate;

    public function mount($updating = false){

        $this->properties = Property::isCompany(current_company()->id)->get() ?? null;
        $this->property = current_property()->id ?? null;

        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->addDays($this->period)->format('Y-m-d');

        $this->loadData();

        $this->monthlyBookings = $this->getMonthlyBookings();
    }

    public function getMonthlyBookings()
    {
        // Fetch monthly revenue data (confirmed + canceled) for the current year
        $bookings = Booking::isCompany(current_company()->id)

            ->whereHas('unit', function ($query) {
                $query->when($this->property, fn($q, $id) => $q->isProperty($id));
            })
            ->with(['unit' => function ($query) {
                $query->with(['unitType' => fn($subQuery) =>
                    $subQuery->when($this->type, fn($q, $type) => $q->isType($type))
                ]);
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

    public function loadData($property = null){
        if($property){
            $this->property = $property;
        }

        $this->units = PropertyUnit::isCompany(current_company()->id)->isProperty($this->property)->get();
        $this->unitTypes = PropertyUnitType::isCompany(current_company()->id)->isProperty($this->property)->get();
        $this->guests = Guest::isCompany(current_company()->id)->get();

        $currentStart = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        // Get the difference in days (as integer)
        $period = $currentStart->diffInDays($endDate);
        $previousStart = Carbon::now()->subDays($period * 2);

        // Fetch both current and previous period bookings
        $currentBookings = Booking::isCompany(current_company()->id)
            ->whereHas('unit', function ($query) {
                $query->when($this->property, fn($q, $id) => $q->isProperty($id));
            })
            ->with(['unit' => function ($query) {
                $query->with(['unitType' => fn($subQuery) =>
                    $subQuery->when($this->type, fn($q, $type) => $q->isType($type))
                ]);
            }])
            // ->where('status', 'confirmed') // Assuming 'status' column exists

            ->orderByDesc('total_amount')
            ->whereBetween('created_at', [$currentStart, $endDate])
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
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }]) // Adds bookings_count for the last 7 days
            ->withSum(['bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }]) // Adds bookings_count for the last 7 days
            ->withSum(['bookings' => function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
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
                $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }]) // Include only bookings from the last 7 days
            ->withCount(['bookings' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }]) // Count bookings for the last 7 days
            ->withSum(['bookings' => function ($subQuery) {
                $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
            }], 'total_amount'); // Sum total amount for the last 7 days
        }])
        ->get()
        ->map(function ($type) {
            $totalRevenue = $type->units->sum('bookings_sum_total_amount') ?? 0;
            $totalBookings = $type->units->sum('bookings_count');

            return [
                'name' => $type->name,
                'total_bookings' => $totalBookings,
                'total_revenue' => $totalRevenue,
            ];
        })
        ->sortByDesc('total_revenue'); // Sort by revenue descending
    }

    public function updatedPeriod($property){
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

    public function updatedProperty($property){
        $this->loadData($this->property);
    }

    public function render()
    {
        return view('channelmanager::livewire.dashboards.reservation');
    }


    public function export(ReportExportService $exportService)
    {
        $query = Booking::query()->with(['guest', 'unit']);

        // Filter by date range if provided
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
        }

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Bookings' => ['value' => $this->bookings->count(), 'change' => "{$this->bookingRateChange}%"],
            'Revenue' => ['value' => format_currency($this->revenue), 'change' => "{$this->revenueChange}%"],
            'Average Bookings' => ['value' => format_currency($this->avgRevenue), 'change' => "{$this->averageRevenueChange}%"],
            'Cancelation Rate' => ['value' => "{$this->cancellationRate}%", 'change' => $this->cancellationRate],
        ];

        // Get Top Bookings (Latest confirmed/completed bookings)
        $topBookings = Booking::isCompany(current_company()->id)
        ->orderByDesc('total_amount')->whereIn('status', ['confirmed', 'completed'])
            ->with('unit', function ($query){
                $query->when($this->property, function ($subQuery) {
                    $subQuery->where('property_id', $this->property);
                });
            })
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'room' => $b->unit->name ?? 'N/A',
                'guest' => $b->guest->name ?? 'Unknown',
                'agent' => $b->agent->name ?? 'N/A',
                'revenue' => $b->total_amount ?? 0,
            ]);

        // Get Top Rooms (Most booked)
        $topRooms = PropertyUnit::isCompany(current_company()->id)
                ->when($this->property, function ($query) {
                    $query->where('property_id', $this->property);
                })
                ->with(['bookings' => function ($subQuery) {
                    $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }])
                ->withCount(['bookings' => function ($subQuery) {
                    $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }])
                ->withSum(['bookings' => function ($subQuery) {
                    $subQuery->whereBetween('created_at', [$this->startDate, $this->endDate]);
                }], 'total_amount')
                ->get()
                ->map(function ($unit) {
                    return [
                        'name' => $unit->name,
                        'room_type' => $unit->unitType->name,
                        'total_bookings' => $unit->bookings_count ?? 0,
                        'total_revenue' => $unit->bookings_sum_total_amount ?? 0,
                    ];
                })
                ->sortByDesc('total_revenue');

        // Assign to detailed sections
        $detailedSections = [
            'Top Bookings' => $topBookings,
            'Top Rooms' => $topRooms,
            'Top Room Types' => $this->roomTypes,
        ];

        // ✅ Export Report
        return $exportService->export('Bookings Report', $summaryData, $detailedSections, 'xlsx');
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
