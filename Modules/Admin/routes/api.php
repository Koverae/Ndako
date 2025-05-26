<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\Api\NdakoApiController;

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


Route::post('/register', [NdakoApiController::class, 'register'])->middleware('throttle:60,1');
Route::get('/download', [NdakoApiController::class, 'download'])->middleware('throttle:60,1');
Route::post('/check-ndako-app', [NdakoApiController::class, 'checkNdakoApp'])->middleware('throttle:60,1');
