<?php

use Illuminate\Support\Facades\Route;
use Modules\FrontDesk\Http\Controllers\FrontDeskController;
use Modules\FrontDesk\Livewire\Interface\Home;
use Modules\FrontDesk\Livewire\Overview;

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

Route::middleware('identify-kover')->group( function () {
    Route::resource('frontdesk', FrontDeskController::class)->names('frontdesk');
    Route::get('/pos/overview', Overview::class)->name('pos.overview');
    Route::prefix("pos/ui")->group(function() {
        Route::get('/', Home::class)->name('pos.ui');
    });
});
