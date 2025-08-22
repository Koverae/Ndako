<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\Auth\AuthController;
use Modules\Admin\Livewire\Overview;

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
// Route::get('/admin', function () {
//     return redirect('/web');
// });

Route::name('admin.')->middleware('web')->group(function () {
    // Guest routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('forgot-password', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'reset'])->name('password.update');
    // Route::group(function () {
    // });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        // Route::get('/', fn () => 'Welcome, Admin!')->name('dashboard');
        Route::get('/', Overview::class)->name('dashboard');
        Route::get('/dashboard', Overview::class);
    });
});
