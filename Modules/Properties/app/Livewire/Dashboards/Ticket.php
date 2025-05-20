<?php

namespace Modules\Properties\Livewire\Dashboards;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Properties\Models\Property\PropertyUnit;
use Modules\Settings\Models\WorkItem;

class Ticket extends Component
{
    public $agent;
    public $period = 7, $ticketsThisDay = 0, $ticketsAssigned = 0, $ongoingTickets = 0, $ticketsClosed = 0, $overdueIssues = 0, $avgCompletionTime = 0;
    public $currentTickets, $ticketsByCategory, $ticketsByRoom;
    public $startDate, $endDate;

    public function mount(){

        $this->startDate = Carbon::today()->subDays($this->period)->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');

        $this->loadData();
    }

    public function loadData(){

        $currentStart = Carbon::now()->subDays($this->period);
        $previousStart = Carbon::now()->subDays($this->period * 2);
        $now = Carbon::now();

        // Filter tickets based on the selected period
        $this->currentTickets = WorkItem::isCompany(current_company()->id)
            ->isTasks()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        $this->ticketsThisDay = $this->currentTickets->count();

        $this->ticketsAssigned = $this->currentTickets->whereNotNull('assigned_to')->count();

        $this->ticketsClosed = $this->currentTickets->whereIn('status', ['resolved', 'completed'])->count();

        $this->ticketsClosed = $this->currentTickets->whereIn('status', 'in_progess')->count();

        $categories = [
            'Plumbing', 'Electrical', 'HVAC', 'Housekeeping', 'Carpentry',
            'Painting', 'Security', 'IT & Networking', 'Kitchen & Appliances',
            'Laundry & Dry Cleaning', 'Landscaping & Outdoor Maintenance',
            'Pest Control', 'Fire Safety', 'Elevators & Escalators',
            'Furniture & Fixtures', 'Room Renovations', 'Guest Complaints & Requests'
        ]; // Define all possible categories

        $this->ticketsByCategory = collect($categories)->mapWithKeys(function ($category) {
            $tickets = $this->currentTickets->where('category', $category);

            return [$category => [
                'total' => $tickets->count(),
                'open' => $tickets->whereNotIn('status', ['resolved', 'completed'])->count(),
                'closed' => $tickets->whereIn('status', ['resolved', 'completed'])->count(),
            ]];
        })
        ->sortByDesc('total') // Sort by total tickets (highest to lowest)
        ->sortByDesc('closed') // Prioritize categories with more closed tickets
        ->take(6); // Only take the top 6

        $this->ticketsByRoom = $this->currentTickets
        ->map(function ($ticket) {
            return [
                'room_name' => $ticket->room ? $ticket->room->name : 'Unknown Room', // Get room name
                'category' => $ticket->category, // Get category
                'status' => $ticket->status, // Get status
            ];
        })
        ->sortBy('room_name')
        ->take(6); // Sort by room name

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
    
    public function render()
    {
        return view('properties::livewire.dashboards.ticket');
    }
}
