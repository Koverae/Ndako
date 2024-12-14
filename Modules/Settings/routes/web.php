<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;
use Modules\Settings\Livewire\GeneralSetting;
use Modules\Settings\Livewire\Users\Lists as UserLists;
use Modules\Settings\Livewire\Users\Show as UserShow;
use Modules\Settings\Livewire\Users\Create as UserCreate;

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
    Route::get('/settings', GeneralSetting::class)->name('settings.general');
    Route::get('/users', UserLists::class)->name('settings.users');
    Route::get('/users/create', UserCreate::class)->name('settings.users.create');
    Route::get('/users/{user}', UserShow::class)->name('settings.users.show');
});
