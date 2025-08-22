<?php

namespace Modules\ChannelManager\Livewire\Table;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\App\Livewire\Components\Table\Table;
use Modules\App\Traits\Table\HasCalendar;
use Modules\ChannelManager\Models\Booking\Booking;
use Modules\ChannelManager\Services\Booking\BookingService;
use Modules\Properties\Models\Property\PropertyFloor;
use Modules\Properties\Models\Property\PropertyUnit;
// If your Property model lives elsewhere, adjust this import:
use Modules\Properties\Models\Property\Property;

class BookingTable extends Table
{
    use HasCalendar;

    public array $data = [];
    public $unitID;

    public ?int $selectedProperty = null;
    public ?int $selectedFloor = null;
    public ?int $selectedUnit = null;

    public $properties;
    public $floors;
    public $units;

    public $events = [];

    protected BookingService $bookingService;

    public function boot(BookingService $bookingService): void
    {
        $this->bookingService = $bookingService;
    }

    public function mount($events = [], $options = []): void
    {
        $this->view_type = 'calendar';
        $this->view = 'app::livewire.components.table.template.calendar';
        $this->data = ['integration_status', 'last_sync_date'];

        $this->unitID = request()->query('unit', null);
        $this->selectedProperty = request()->query('property') ? (int) request()->query('property') : null;
        $this->selectedFloor = request()->query('floor') ? (int) request()->query('floor') : null;

        $this->options = array_merge([
            'initialView' => 'dayGridMonth',
            'editable' => true,
            'selectable' => true,
        ], $options);

        $this->hydrateCollections();
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

    /** Base query with cascading filters (property → floor → unit) */
    public function query(): Builder
    {
        $q = Booking::query();

        // Property scope: via unit.property_id
        if ($this->selectedProperty) {
            $propId = $this->selectedProperty;
            $q->whereHas('unit', fn ($u) => $u->where('property_id', $propId));
        }

        // Floor scope: via unit.floor_id
        if ($this->selectedFloor) {
            $floorId = $this->selectedFloor;
            $q->whereHas('unit', fn ($u) => $u->where('floor_id', $floorId));
        }

        // Unit scope (from chip selection or deep link)
        if ($this->selectedUnit) {
            $q->where('property_unit_id', $this->selectedUnit);
        } elseif ($this->unitID) {
            $q->where('property_unit_id', $this->unitID);
        }

        // Optional server-side search (if you expose $this->searchQuery)
        if ($this->searchQuery ?? false) {
            $search = '%' . $this->searchQuery . '%';
            $q->where(function ($s) use ($search) {
                $s->where('reference', 'like', $search)
                    ->orWhereHas('guest', fn ($g) => $g->where('name', 'like', $search))
                    ->orWhereHas('unit', fn ($u) => $u->where('name', 'like', $search));
            });
        }

        return $q;
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
        return [ Card::make('name', 'name', '', $this->data) ];
    }

    /** Read bookings → normalize to FC events → push to browser */
    public function loadBookings(): void
    {
        $this->events = $this->query()
            ->with(['unit', 'guest', 'unit.unitType', 'channel'])
            ->get()
            ->map(function (Booking $b) {
                $status = strtolower($b->status);
                return [
                    'id' => $b->id,
                    'title' => $b->unit->name ?? 'Unknown Unit',
                    'start' => Carbon::parse($b->check_in)->toDateTimeString(),
                    'end' => Carbon::parse($b->check_out)->toDateTimeString(),
                    'backgroundColor' => $this->getStatusColor($status),
                    'borderColor' => $this->getStatusColor($status),
                    'extendedProps' => [
                        'unitId' => $b->property_unit_id,
                        'reference' => $b->reference ?? 'N/A',
                        'guest' => $b->guest->name ?? 'N/A',
                        'room' => $b->unit->name ?? 'N/A',
                        'unitType' => $b->unit->unitType->name ?? 'N/A',
                        'channel' => inverseSlug($b->source) ?? 'Direct Booking',
                        'status' => ucfirst($status),
                    ],
                ];
            })->toArray();

        // Live refresh on the calendar
        $this->dispatch('calendarUpdated', $this->events);
    }

    public function getStatusColor(string $status): string
    {
        return match ($status) {
            'pending'   => '#fbc02d',
            'confirmed' => '#017E84',
            'completed' => '#1e88e5',
            'canceled'  => '#e53935',
            default     => '#757575',
        };
    }

    #[On('updateBookingDate')]
    public function updateBookingDate($bookingId, $start, $end): void
    {
        $this->bookingService->updateBookingDate($bookingId, $start, $end);
        // Reload to reflect in the UI without a hard redirect:
        $this->loadBookings();
    }

    /** ========== Filters ========== */

    public function selectProperty($propertyId): void
    {
        $this->selectedProperty = $propertyId ? (int) $propertyId : null;
        // Reset dependent filters when property changes
        $this->selectedFloor = null;
        $this->selectedUnit = null;
        $this->hydrateCollections();
        $this->loadBookings();
    }

    public function selectFloor($floorId): void
    {
        $this->selectedFloor = $floorId ? (int) $floorId : null;
        // When floor changes, reset unit and reload units
        $this->selectedUnit = null;
        $this->hydrateUnitsOnly();
        $this->loadBookings();
    }

    public function selectUnit($unitId): void
    {
        Log::debug("selectUnit called with unitId: {$unitId}");
        $this->selectedUnit = (int) $unitId;
        $this->loadBookings();
    }

    #[On('clearUnitFilter')]
    public function clearUnitFilter(): void
    {
        $this->selectedProperty = null;
        $this->selectedFloor = null;
        $this->selectedUnit = null;
        $this->hydrateCollections();
        $this->loadBookings();
    }

    /** Load properties, floors & units according to selection */
    protected function hydrateCollections(): void
    {
        $companyId = current_company()->id;

        // Properties (all company)
        $this->properties = Property::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id','name']);

        // Floors scoped to property (or all)
        $floorsQuery = PropertyFloor::isCompany($companyId);
        if ($this->selectedProperty) {
            $floorsQuery->where('property_id', $this->selectedProperty);
        }
        $this->floors = $floorsQuery->orderBy('name')->get();

        // Units scoped to property/floor (and always eager load unitType)
        $this->hydrateUnitsOnly();
    }

    /** Load units only (respecting current property + floor) */
    protected function hydrateUnitsOnly(): void
    {
        $companyId = current_company()->id;

        $units = PropertyUnit::isCompany($companyId)
            ->when($this->selectedProperty, fn ($q) => $q->where('property_id', $this->selectedProperty))
            ->when($this->selectedFloor, fn ($q) => $q->where('floor_id', $this->selectedFloor))
            ->with('unitType')
            ->orderBy('name')
            ->get();

        $this->units = $units;
    }
}
