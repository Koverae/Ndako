<?php

use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\ChannelManagerController;

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

Route::middleware('check-allowed-domains')->get('/get-embed-config', [BookingFormController::class, 'getEmbedConfig']);

Route::middleware(['throttle:60,1','checkApiKey'])->group(function () {


    Route::get('/rooms/{roomId}', function (Request $request) {
        // Log::info("Received Room ID: $request->roomId");  // Log received roomId
        $room = PropertyUnit::find($request->roomId);
    
        if (!$room) {
            return response()->json(['message' => 'Room not found.'], 404);
        }
    
        return response()->json([
            'name' => $room->name,
            'type' => $room->unitType->name,
            'price' => $room->unitType->price,
            'details' => $room->description,
        ]);
    });

    Route::get('/embed/form', [BookingFormController::class, 'embed']);
    Route::get('/available-rooms-html', [BookingFormController::class, 'availableRoomsHtml']);
    Route::post('/check-availability', [BookingFormController::class, 'checkAvailability']);
    Route::post('/confirm-booking', [BookingFormController::class, 'confirmBooking']);
});