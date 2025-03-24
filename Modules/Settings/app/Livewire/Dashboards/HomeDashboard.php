<?php

namespace Modules\Settings\Livewire\Dashboards;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\Settings\Models\WorkItem;
use Modules\Settings\Notifications\MultiChannelNotification;
use Livewire\Attributes\On;
use Modules\Properties\Models\Property\PropertyUnit;

class HomeDashboard extends Component
{
    public $tasks, $situations = [];
    public $property, $bookings, $guestsCurrentlyStaying, $checkinsToday, $checkoutsToday;
    public $occupancyRate, $occupiedNights = 0, $occupiedRooms = 0, $totalNightsAvailable = 0;

    public $period = 1, $currentTickets, $ticketsThisDay = 0, $ticketsAssigned = 0, $ongoingTickets = 0, $ticketsClosed = 0;

    public function mount()
    {
        $this->property = current_property()->id ?? null;

        $this->loadBookings();

        $this->situations = WorkItem::isCompany(current_company()->id)->isSituations()
            ->where('assigned_to', Auth::user()->id)
            ->orWhere('assigned_to', null)
            ->where('reported_by', Auth::user()->id)
            ->get();
        $this->loadTickets();
    }

    public function loadTickets(){

        $currentStart = Carbon::now()->subDays($this->period);
        $previousStart = Carbon::now()->subDays($this->period * 2);
        $now = Carbon::now();

        // Filter tickets based on the selected period
        $this->currentTickets = WorkItem::isCompany(current_company()->id)
            ->isTasks()
            ->whereBetween('created_at', [$currentStart, $now])
            ->get();

        $this->ticketsThisDay = $this->currentTickets->count();

        $this->ticketsAssigned = $this->currentTickets->where('assigned_to', Auth::user()->id)->count();

        $this->ticketsClosed = $this->currentTickets->where('assigned_to', Auth::user()->id)->whereIn('status', ['resolved', 'completed'])->count();

        $this->ongoingTickets = $this->currentTickets->where('assigned_to', Auth::user()->id)->whereIn('status', ['in_progress'])->count();
    }

    public function loadBookings()
    {
        $today = Carbon::today();

        $startDate = Carbon::now()->subDays($this->period ?? 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $propertyId = $this->property;
        $now = Carbon::now();

        // Fetch both current and previous period bookings
        $this->bookings = Booking::isCompany(current_company()->id)
            ->whereHas('unit', function ($query){
                $query->isProperty($this->property);
            })
            // ->orWhereDate('check_in', '=<', $today)
            ->whereDate('check_out', '>=', $today)
            ->where('check_out_status', 'pending')
            ->whereIn('status', ['confirmed']) // Assuming 'status' column exists
            ->get();

        // Guests currently staying in the hotel
        $this->guestsCurrentlyStaying = Booking::whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>=', $today)
            ->whereIn('check_in_status', ['checked_in'])
            ->count();

        // Guests checking out today
        $this->checkoutsToday = Booking::whereDate('check_out', $today)
            ->whereIn('status', ['confirmed']) // Assuming 'status' column exists
            ->whereIn('check_in_status', ['checked_in'])
            ->count();
        // Guests checking in today
        $this->checkinsToday = Booking::whereDate('check_in', $today)
            ->whereIn('status', ['confirmed']) // Assuming 'status' column exists
            ->count();


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

    }


    public function render()
    {
        return view('settings::livewire.dashboards.home-dashboard');
    }

    #[On('load-work-items')]
    public function loadWorkItems(){

        $this->tasks = WorkItem::isCompany(current_company()->id)->isTasks()
            ->where('assigned_to', Auth::user()->id)
            ->orWhere('assigned_to', null)
            ->get();

        $this->situations = WorkItem::isCompany(current_company()->id)->isSituations()
            ->where('reported_by', Auth::user()->id)
            ->where('assigned_to', Auth::user()->id)
            ->orWhere('assigned_to', null)
            ->get();
    }

    public function openTicket($id){
        $ticket = WorkItem::find($id);

        $ticket->update([
            'status' => 'in_progress'
        ]);

        $this->loadTickets();
    }

    public function closeTicket($id){
        $ticket = WorkItem::find($id);

        $ticket->update([
            'status' => 'resolved'
        ]);

        $this->loadTickets();
    }

}
