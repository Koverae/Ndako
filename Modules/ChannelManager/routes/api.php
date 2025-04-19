<?php

use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\ChannelManagerController;
use Modules\ChannelManager\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\ChannelManager\Http\Controllers\Embed\BookingFormController;
use Modules\Properties\Models\Property\PropertyUnit;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('channelmanager', ChannelManagerController::class)->names('channelmanager');
});

// Route::get('/embed/form', [BookingFormController::class, 'embed']);

Route::prefix('v1')->group(function () {

    Route::get('/embed/booking.js', function () {
        return response(file_get_contents(resource_path('js/embed-booking.js')), 200, [
            'Content-Type' => 'application/javascript',
        ]);
    });
    
    Route::middleware('check-allowed-domains')->get('/get-embed-config', [BookingFormController::class, 'getEmbedConfig']);
});


Route::middleware(['throttle:60,1','checkApiKey'])->group(function () {

    Route::prefix('v1')->group(function () {
        Route::get('rooms', [UnitController::class, 'index']);
        Route::get('room-types', [UnitController::class, 'typeIndex']);
        Route::get('room-types/{id}', [UnitController::class, 'typeShow']);
        // Route::apiResource('rooms', UnitController::class);

        // Embed

    });
    Route::get('/embed/form', [BookingFormController::class, 'embed']);
    Route::get('/available-rooms-html', [BookingFormController::class, 'availableRoomsHtml']);
    Route::post('/check-availability', [BookingFormController::class, 'checkAvailability']);
    Route::post('/confirm-booking', [BookingFormController::class, 'confirmBooking']);
});
