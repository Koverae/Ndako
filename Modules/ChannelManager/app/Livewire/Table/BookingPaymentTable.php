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
    //     return route('properties.units.create');
    // }


    public function showRoute($id) : string
    {
        return route('channels.show', ['channel' => $id]);
    }

    public function emptyTitle(): string
    {
        return 'No Payments Recorded';
    }

    public function emptyText(): string
    {
        return 'Payments related to bookings will appear here. Add a new payment to keep track of transactions effortlessly.';
    }

    public function query() : Builder
    {
        $query = BookingPayment::query();


        // Apply the search query filter if a search query is present
        if ($this->searchQuery) {
            // Search both the booking's name and the related guest's name
            $query = BookingPayment::query()
            ->where('reference', 'like', '%' . $this->searchQuery . '%')
            ->orWhereHas('invoice', function($query) {
                $query->where('reference', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // 🎯 Filters
        if (!empty($this->filters)) {
            foreach ($this->filters as $field => $value) {
                $query->where($field, $value);
            }
        }

        return $query;
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
