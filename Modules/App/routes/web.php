<?php

use Illuminate\Support\Facades\Route;
use Modules\App\Http\Controllers\AppController;
use Modules\App\Http\Controllers\PaymentGateway\PaystackController;
use Modules\App\Livewire\Subscription\SubscriptionPage;

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
    // Route::resource('app', AppController::class)->names('apps');
    Route::get('/subcribe', SubscriptionPage::class)->name('subscribe');

    Route::post('/paystack/pay', [PaystackController::class, 'initiate'])->name('paystack.pay');
    Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');
});
