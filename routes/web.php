<?php

use App\Http\Controllers\Auth\GetStartedController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard;
use App\Livewire\Dashboards\Overview;
use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\Embed\BookingFormController;
use Modules\App\Livewire\GettingStarted;
use Modules\App\Livewire\Onboarding;

// Route::get('/', function () {
//     return redirect('/web');
// });

Route::middleware(['auth', 'verified'])->prefix('web')->group(function () {
    Route::get('/getting_started', [GetStartedController::class, 'index'])->name('getting-started');
});

Route::middleware(['auth', 'verified', 'identify-kover'])->prefix('web')->group(function () {

    Route::get('/onboarding', Onboarding::class)->name('onboarding');
    Route::get('/', Overview::class)->name('dashboard');
});

// require __DIR__.'/auth.php';


Route::get('/embed-booking.js', function () {
    return response(file_get_contents(resource_path('js/embed-booking.js')), 200, [
        'Content-Type' => 'application/javascript',
    ]);
});
