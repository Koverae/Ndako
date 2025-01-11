<?php

namespace Modules\ChannelManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\ChannelManager\Models\Booking\Booking;

class BookingTable extends Table
{
    public array $data = [];

    public function mount(){
        $this->data = ['integration_status', 'last_sync_date'];
    }

    // public function createRoute() : string
    // {
    //     return Route::subdomainRoute('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return Route::subdomainRoute('bookings.show', ['booking' => $id]);
    }

    public function emptyTitle() : string
    {
        return '';
    }

    public function emptyText() : string
    {
        return '';
    }
    public function query() : Builder
    {
        return Booking::query(); // Returns a Builder instance for querying the User model
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
}
