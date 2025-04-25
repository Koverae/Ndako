<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Koverae\KoveraeBilling\Controllers\Payments\PaystackController as PaymentsPaystackController;
use Modules\App\Http\Controllers\AppController;
use Modules\App\Http\Controllers\PaymentGateway\PaystackController;
use Modules\App\Livewire\ImportFile;
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

    Route::get('/import/{model}', ImportFile::class)->name('import.records');

    Route::get('/subcribe', SubscriptionPage::class)->name('subscribe');

    // Paystack Payment
    Route::post('/paystack/pay', [PaystackController::class, 'initiate'])->name('paystack.pay');
    Route::get('/paystack/callback', [PaystackController::class, 'callback'])->name('paystack.callback');
    // Route::get('/paystack/callback', function (Request $request) {
    // })->name('paystack.callback');

    // Koverae Billing
    Route::get('/koverae-billing/paystack/callback', [PaymentsPaystackController::class, 'callback'])->name('billing.paystack.callback');
    Route::get('/koverae-billing/paystack/webhook', [PaymentsPaystackController::class, 'handle'])->name('billing.paystack.webhook');
});
