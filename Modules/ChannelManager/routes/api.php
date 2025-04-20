<?php

use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\ChannelManagerController;
use Modules\ChannelManager\Http\Controllers\Api\V1\UnitController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Modules\ChannelManager\Http\Controllers\Api\V1\Embed\BookingFormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use Modules\ChannelManager\Http\Controllers\Embed\BookingFormController;
use Modules\Properties\Models\Property\PropertyUnit;

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
    
    Route::post('/embed/initiate-booking', [BookingFormController::class, 'initiate'])->name('initiate-booking');
    // Route::get('/embed/confirm-booking/{id}', [BookingFormController::class, 'confirm'])->name('booking.confirm');
});


Route::middleware(['throttle:60,1','checkApiKey'])->group(function () {

    Route::prefix('v1')->group(function () {
        Route::get('rooms', [UnitController::class, 'index']);
        Route::get('room-types', [UnitController::class, 'typeIndex']);
        Route::get('room-types/{id}', [UnitController::class, 'typeShow']);
        // Route::apiResource('rooms', UnitController::class);

        // Embed
        Route::get('/embed/form', [BookingFormController::class, 'embed']);
        Route::post('/check-availability', [BookingFormController::class, 'checkAvailability']);
        Route::get('/available-rooms-html', [BookingFormController::class, 'availableRoomsHtml']);
        Route::get('/embed/rooms/{roomId}', function (Request $request) {
            // Log::info("Received Room ID: $request->roomId");  // Log received roomId
            $room = PropertyUnit::find($request->roomId);
        
            if (!$room) {
                return response()->json(['message' => 'Room not found.'], 404);
            }
        
            return response()->json([
                'id' => $room->id,
                'name' => $room->name,
                'type' => $room->unitType->name,
                'price' => $room->unitType->price,
                'details' => $room->description,
            ]);
        });
        Route::get('/confirm-booking-html/{roomId}', [BookingFormController::class, 'confirmBookingHtml']);
        Route::post('/embed/confirm-booking', [BookingFormController::class, 'confirmBooking']);
        
    });
});
