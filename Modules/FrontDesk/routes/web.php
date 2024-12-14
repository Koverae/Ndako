<?php

use Illuminate\Support\Facades\Route;
use Modules\FrontDesk\Http\Controllers\FrontDeskController;

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

Route::group([], function () {
    Route::resource('frontdesk', FrontDeskController::class)->names('frontdesk');
});
