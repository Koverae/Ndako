<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Dashboard;
use App\Livewire\Dashboards\Overview;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/web');
});


Route::prefix('/web')->middleware(['auth', 'twofactor'])->group(function () {
    
    Route::get('/', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/dashboards', Overview::class)->middleware(['auth', 'verified'])->name('dashboards.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
