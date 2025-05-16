<?php

namespace Modules\ChannelManager\Livewire\Dashboards;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Illuminate\Support\Facades\DB;
use Modules\App\Services\ReportExportService;
use Modules\Properties\Models\Property\PropertyUnitType;

class Room extends Component
{

    public $period = 7  , $property;
    public $bestSellerRoom, $bestSellerType, $rooms, $roomTypes;
    public $properties, $bestSellerRooms;
    public $startDate, $endDate;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = current_property()->id ?? null;

        $this->startDate = Carbon::today()->subDays($this->period)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');

        $this->loadData();
    }

    public function loadData($property = null){
        if($property){
            $this->property = $property;
        }

        $this->rooms = PropertyUnit::isCompany(current_company()->id)
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['bookings' => function ($query) {
            $query->select('id', 'property_unit_id', 'total_amount', DB::raw('DATEDIFF(check_out, check_in) as nights'))
            ->whereBetween('check_in', [$this->startDate, $this->endDate])
            ->orWhereBetween('check_out', [$this->startDate, $this->endDate]);
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

        $this->bestSellerRoom = $this->rooms->first(); // Get the top room

        $this->roomTypes = PropertyUnitType::isCompany(current_company()->id)
        ->when($this->property, function ($query) {
            $query->where('property_id', $this->property); // Apply filter if $property is set
        })
        ->with(['units.bookings' => function ($query) {
            $query->select('id', 'property_unit_id', DB::raw('DATEDIFF(check_out, check_in) as nights'), 'total_amount')
            ->whereBetween('check_in', [$this->startDate, $this->endDate])
            ->orWhereBetween('check_out', [$this->startDate, $this->endDate]);
        }])
        ->get()
        ->map(function ($type) {
            $totalRevenue = $type->units->flatMap->bookings->sum('total_amount');
            $totalNights = $type->units->flatMap->bookings->sum('nights');

            return [
                'type_name' => $type->name,
                'total_revenue' => $totalRevenue,
                'total_nights' => $totalNights,
            ];
        })
        ->sortByDesc('total_revenue') // Sort types by revenue descending
        ->values(); // Re-index the collection

        $this->bestSellerType = $this->roomTypes ->first(); // Get the top room type

        // Fetch Best Selling Rooms within the period
        $this->bestSellerRooms = PropertyUnit::isCompany(current_company()->id)->isProperty($this->property)
            ->with(['bookings' => function($query) {
                $query->with(['unit' => function ($subQuery) {
                    $subQuery->when($this->property, function ($query) {
                        $query->where('property_id', $this->property); // Apply filter if $property is set
                    });
                }])
                ->select(
                    'property_unit_id',
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('check_in', [$this->startDate, $this->endDate])
                ->groupBy('property_unit_id');
            }])
            ->get()
            ->map(function ($room) {
                $revenue = $room->bookings->sum('revenue');
                return [
                    'room_name' => 'Room '.$room->name,
                    'revenue' => $revenue,
                ];
            })
            ->sortByDesc('revenue');  // Sort by revenue descending

    }

    public function updatedPeriod(){
        $this->loadData($this->property);
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
        return view('channelmanager::livewire.dashboards.room');
    }


    public function export(ReportExportService $exportService)
    {

        // ✅ Summary Data (Example: Dashboard Stats)
        $summaryData = [
            'Best Seller' => ['value' => $this->bestSellerRoom['room_name'], 'change' => "{$this->bestSellerRoom['total_nights']}"],
            'Best Type' => ['value' => $this->bestSellerType['type_name'], 'change' => "{$this->bestSellerType['total_nights']}"],
        ];


        // Assign to detailed sections
        $detailedSections = [
            'Best Selling Rooms' => $this->rooms,
            'Best Selling Room Types' => $this->roomTypes,
        ];

        // ✅ Export Report
        return $exportService->export('Rooms Report', $summaryData, $detailedSections, 'xlsx');
    }
}
