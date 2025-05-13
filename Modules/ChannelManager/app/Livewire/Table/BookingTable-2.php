<?php

namespace Modules\ChannelManager\Livewire\Table;

use Carbon\Carbon;
use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Traits\Table\HasCalendar;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Services\Booking\BookingService;

class BookingTable extends Table
{
    use HasCalendar;

    public array $data = [];
    public $unitID;
    protected $bookingService;

    public function boot(BookingService $bookingService){
        $this->bookingService = $bookingService;
    }

    public function mount($events = [], $options = []){

        $this->view_type = 'calendar';
        $this->view = 'app::livewire.components.table.template.calendar';
        $this->loadBookings();

        $this->data = ['integration_status', 'last_sync_date'];
        $this->unitID = request()->query('unit', null);

        // Calendar View
        // $this->events = $events;
        $this->options = array_merge([
            'initialView' => 'dayGridMonth',
            'editable' => false,
        ], $options);
    }

    // public function createRoute() : string
    // {
    //     return route('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return route('bookings.show', ['booking' => $id]);
    }


    public function emptyTitle(): string
    {
        return 'No Reservations Yet';
    }

    public function emptyText(): string
    {
        return 'Your reservations will appear here once added. Start by creating a new reservation to manage your bookings seamlessly.';
    }


    public function query() : Builder
    {
        $query = Booking::query();

        // Filter by property ID from the URL
        if ($this->unitID) {
            $query->isUnit($this->unitID);
        }

        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = Booking::query()
            ->where('reference', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('guest', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            })
            ->orWhereHas('unit', function($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            });
        }

        return $query; // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('reference', __('Reference'))->component('app::table.column.special.show-title-link'),
            Column::make('guest_id', __('Guest'))->component('app::table.column.special.contact.simple'),
            Column::make('property_unit_id', __('Room'))->component('app::table.column.special.property-unit'),
            Column::make('check_in', __('Check In'))->component('app::table.column.special.date.basic'),
            Column::make('check_out', __('Check Out'))->component('app::table.column.special.date.basic'),
            Column::make('id', __('Days'))->component('app::table.column.special.booking.booking-days'),
            Column::make('guests', __('N° Guests'))->component('app::table.column.special.booking.guests'),
            Column::make('total_amount', __('Total Price'))->component('app::table.column.special.price'),
            Column::make('paid_amount', __('Paid Off'))->component('app::table.column.special.price'),
            Column::make('due_amount', __('Debt'))->component('app::table.column.special.price'),
            Column::make('status', __('Status'))->component('app::table.column.special.booking.booking-status'),
        ];
    }

    // Kanban View
    public function cards() : array
    {
        return [
            Card::make('name', "name", "", $this->data),
        ];
    }

    // Calendar View
    public function loadBookings()
    {
        $this->events = $this->data()->map(function ($booking) {

            return [
                'id'    => $booking->id,
                'title' => $booking->unit->name,
                'start' => $booking->check_in,
                'end'   => Carbon::parse($booking->check_out)->addDays(1),
                'color' => $this->getStatusColor($booking->status) ,
                'extendedProps' => [
                    'reference' => $booking->reference,
                    'guest' => $booking->guest->name,
                    'room'  => $booking->unit->name,
                    'unitType'  => $booking->unit->unitType->name,
                    'channel'  => $booking->channel->name ?? 'Direct Booking',
                    'status' => ucfirst($booking->status),
                ]
            ];
        })->toArray();

        $this->dispatch('calendarUpdated', ['events' => $this->events]);
    }

    public function getStatusColor($status) {
        switch ($status) {
            case 'pending':
                return '#fbc02d'; // Yellow
            case 'confirmed':
                return '#017E84'; // Green
            case 'completed':
                return '#1e88e5'; // Blue
            case 'canceled':
                return '#e53935'; // Red
            default:
                return '#757575'; // Gray (Fallback)
        }
    }

    public function getCheckInStatus($booking){
        //
    }

    #[On('updateBookingDate')]
    public function updateBookingDate($bookingId, $start, $end)
    {
        $this->bookingService->updateBookingDate($bookingId, $start, $end);
        $this->redirect(route('bookings.lists'), true);
    }

    public function fetchEvents()
    {
        return $this->data()->map(function ($booking) {

            return [
                'id'    => $booking->id,
                'title' => $booking->unit->name,
                'start' => $booking->check_in,
                'end'   => Carbon::parse($booking->check_out)->addDays(1),
                'color' => $this->getStatusColor($booking->status) ,
                'extendedProps' => [
                    'reference' => $booking->reference,
                    'guest' => $booking->guest->name,
                    'room'  => $booking->unit->name,
                    'unitType'  => $booking->unit->unitType->name,
                    'channel'  => $booking->channel->name ?? 'Direct Booking',
                    'status' => ucfirst($booking->status),
                ]
            ];
        })->toArray();
    }

}
