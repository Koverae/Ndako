<?php

namespace Modules\ChannelManager\Livewire\Table;

use Carbon\Carbon;
use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Traits\Table\HasCalendar;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\Properties\Models\Property\PropertyUnit;

class BookingTable extends Table
{
    use HasCalendar;

    public array $data = [];
    public $unitID;
    public $selectedUnit = null;
    public $units;
    public $events = [];
    protected $bookingService;

    public function boot(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function mount($events = [], $options = [])
    {
        $this->view_type = 'calendar';
        $this->view = 'app::livewire.components.table.template.calendar';
        $this->data = ['integration_status', 'last_sync_date'];
        $this->unitID = request()->query('unit', null);
        $this->units = PropertyUnit::isCompany(current_company()->id)->with('unitType')->get();
        $this->options = array_merge([
            'initialView' => 'dayGridMonth',
            'editable' => true,
            'selectable' => true,
        ], $options);
        $this->loadBookings();
    }

    public function showRoute($id): string
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

    public function query(): Builder
    {
        $query = Booking::query();

        if ($this->selectedUnit) {
            $query->where('property_unit_id', $this->selectedUnit);
            Log::debug("Filtering bookings for unit: {$this->selectedUnit}");
        } elseif ($this->unitID) {
            $query->where('property_unit_id', $this->unitID);
        }

        if ($this->searchQuery) {
            $query->where(function ($q) {
                $q->where('reference', 'like', '%' . $this->searchQuery . '%')
                  ->orWhereHas('guest', fn($q) => $q->where('name', 'like', '%' . $this->searchQuery . '%'))
                  ->orWhereHas('unit', fn($q) => $q->where('name', 'like', '%' . $this->searchQuery . '%'));
            });
        }

        return $query;
    }

    public function columns(): array
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

    public function cards(): array
    {
        return [
            Card::make('name', "name", "", $this->data),
        ];
    }

    public function loadBookings()
    {
        $this->events = $this->query()->with(['unit', 'guest', 'unit.unitType', 'channel'])
            ->get()
            ->map(function ($booking) {
                $status = strtolower($booking->status);
                Log::debug("Booking {$booking->id} Status: {$status}, Unit ID: {$booking->property_unit_id}");
                return [
                    'id' => $booking->id,
                    'title' => $booking->unit->name ?? 'Unknown Unit',
                    'start' => Carbon::parse($booking->check_in)->toDateTimeString(),
                    'end' => Carbon::parse($booking->check_out)->toDateTimeString(),
                    'backgroundColor' => $this->getStatusColor($status),
                    'borderColor' => $this->getStatusColor($status),
                    'extendedProps' => [
                        'reference' => $booking->reference ?? 'N/A',
                        'guest' => $booking->guest->name ?? 'N/A',
                        'room' => $booking->unit->name ?? 'N/A',
                        'unitType' => $booking->unit->unitType->name ?? 'N/A',
                        // 'channel' => $booking->channel->name ?? 'Direct Booking',
                        'channel' => inverseSlug($booking->source) ?? 'Direct Booking',
                        'status' => ucfirst($status),
                    ],
                ];
            })->toArray();

        Log::debug("Events loaded: " . json_encode($this->events));
        $this->dispatch('calendarUpdated', ['events' => $this->events]);
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => '#fbc02d',
            'confirmed' => '#017E84',
            'completed' => '#1e88e5',
            'canceled' => '#e53935',
            default => '#757575',
        };
    }

    #[On('updateBookingDate')]
    public function updateBookingDate($bookingId, $start, $end)
    {
        $this->bookingService->updateBookingDate($bookingId, $start, $end);
        // $this->loadBookings();
        $this->redirect(route('bookings.lists'), true);
    }

    public function selectUnit($unitId)
    {
        Log::debug("selectUnit called with unitId: {$unitId}");
        $this->selectedUnit = $unitId;
        $this->loadBookings();
    }

    #[On('clearUnitFilter')]
    public function clearUnitFilter()
    {
        Log::debug("clearUnitFilter called");
        $this->selectedUnit = null;
        $this->loadBookings();
    }

    // public function render()
    // {
    //     return view($this->view, [
    //         'units' => $this->units,
    //         'events' => $this->events,
    //     ]);
    // }
}
