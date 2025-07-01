<?php

use Illuminate\Support\Facades\Route;
use Modules\App\Http\Controllers\Api\V1\Payment\DarajaCallbackController;
use Modules\App\Http\Controllers\AppController;
use Modules\App\Http\Controllers\PaymentGateway\PesapalController;

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
    Route::apiResource('app', AppController::class)->names('app');
});

// Daraja API
Route::prefix('v1/payments/mpesa')->group(function () {
    Route::post('/stk-callback', [DarajaCallbackController::class, 'stkCallback']);
    Route::post('/b2c-result', [DarajaCallbackController::class, 'b2cResult']);
    Route::post('/b2c-timeout', [DarajaCallbackController::class, 'b2cTimeout']);
    Route::post('/c2b-confirmation', [DarajaCallbackController::class, 'c2bConfirmation']);
    Route::post('/c2b-validation', [DarajaCallbackController::class, 'c2bValidation']);
    Route::post('/reversal', [DarajaCallbackController::class, 'reversal']);
});

// Pesapal IPN route
Route::post('/pesapal/ipn', [PesapalController::class, 'handleIPN']);
