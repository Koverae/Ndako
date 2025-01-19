<?php

namespace Modules\ChannelManager\Livewire\Dashboards;

use Carbon\Carbon;
use Livewire\Component;
use Modules\Properties\Models\Property\Property;
use Modules\Properties\Models\Property\PropertyUnit;
use Illuminate\Support\Facades\DB;
use Modules\Properties\Models\Property\PropertyUnitType;

class Room extends Component
{

    public $period = 7, $property;
    public $bestSellerRoom, $bestSellerType, $rooms, $roomTypes;
    public $properties, $bestSellerRooms;

    public function mount(){
        $this->properties = Property::isCompany(current_company()->id)->get();
        $this->property = $this->properties->first()->id;
        $this->loadData();
    }

    public function loadData(){

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

        $this->bestSellerRoom = $this->rooms->first(); // Get the top room

        $this->roomTypes = PropertyUnitType::isCompany(current_company()->id)
        ->with(['units.bookings' => function ($query) {
            $query->select('id', 'property_unit_id', DB::raw('DATEDIFF(check_out, check_in) as nights'), 'total_amount')
            ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
            ->orWhereBetween('check_out', [Carbon::now()->subDays($this->period), Carbon::now()]);
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
        $this->bestSellerRooms = PropertyUnit::isCompany(current_company()->id)
            ->with(['bookings' => function($query) {
                $query->select(
                    'property_unit_id',
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('check_in', [Carbon::now()->subDays($this->period), Carbon::now()])
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
        $this->mount();
    }

    public function updatedProperty($property){
        $this->property = $property;
    }

    public function render()
    {
        return view('channelmanager::livewire.dashboards.room');
    }
}
