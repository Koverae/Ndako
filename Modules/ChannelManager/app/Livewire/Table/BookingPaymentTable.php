<?php

namespace Modules\ChannelManager\Livewire\Table;

use Modules\App\Livewire\Components\Table\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Modules\App\Livewire\Components\Table\Card;
use Modules\App\Livewire\Components\Table\Column;
use Modules\ChannelManager\Models\Booking\BookingPayment;

class BookingPaymentTable extends Table
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
        return Route::subdomainRoute('channels.show', ['channel' => $id]);
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
        return BookingPayment::query(); // Returns a Builder instance for querying the User model
    }

    // List View
    public function columns() : array
    {
        return [
            Column::make('reference', __('Reference')),
            Column::make('booking_invoice_id', __('Invoice'))->component('app::table.column.special.booking.invoice'),
            Column::make('amount', __('Amount'))->component('app::table.column.special.price'),
            Column::make('due_amount', __('Due Amount'))->component('app::table.column.special.price'),
            Column::make('date', __('Payment Date'))->component('app::table.column.special.date.basic'),
        ];
    }
}
