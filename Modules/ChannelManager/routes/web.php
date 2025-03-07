<?php

use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\ChannelManagerController;
use Modules\ChannelManager\Livewire\Channels\Lists as ChannelLists;
use Modules\ChannelManager\Livewire\Channels\Show as ChannelShow;
use Modules\ChannelManager\Livewire\Overview;
use Modules\ChannelManager\Livewire\Bookings\Lists as BookingLists;
use Modules\ChannelManager\Livewire\Bookings\Create as BookingCreate;
use Modules\ChannelManager\Livewire\Bookings\Show as BookingShow;
use Modules\ChannelManager\Livewire\BookingInvoices\Lists as InvoiceLists;
use Modules\ChannelManager\Livewire\BookingInvoices\Create as InvoiceCreate;
use Modules\ChannelManager\Livewire\BookingInvoices\Show as InvoiceShow;
use Modules\ChannelManager\Livewire\Guests\Lists as GuestLists;
use Modules\ChannelManager\Livewire\BookingPayments\Lists as PaymentLists;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('identify-kover')->group(function () {
    // Route::get('channelmanager', ChannelManagerController::class, 'index')->name('channels.index');
    Route::get('channels/overview', Overview::class)->name('channels.index');
    Route::get('channels', ChannelLists::class)->name('channels.lists');
    Route::get('channels/{channel}', ChannelShow::class)->name('channels.show');
    Route::get('/guests', GuestLists::class)->name('guests.lists');

    // Bookings
    Route::prefix('/bookings')->name('bookings.')->group(function() {
        Route::get('/', BookingLists::class)->name('lists');
        Route::get('/create', BookingCreate::class)->name('create');
        Route::get('/{booking}', BookingShow::class)->name('show');

    });

        // Booking Invoices
        Route::prefix('/booking-invoices')->name('bookings.invoices.')->group(function() {
            Route::get('/', InvoiceLists::class)->name('lists');
            Route::get('/create', InvoiceCreate::class)->name('create');
            Route::get('/{invoice}', InvoiceShow::class)->name('show');

        });

        // Booking Payments
        Route::prefix('/booking-payments')->name('bookings.payments.')->group(function() {
            Route::get('/', PaymentLists::class)->name('lists');
            // Route::get('/create', PaymentCreate::class)->name('create');
            // Route::get('/{invoice}', PaymentShow::class)->name('show');

        });
});
