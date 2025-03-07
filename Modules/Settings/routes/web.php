<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;
use Modules\Settings\Livewire\GeneralSetting;
use Modules\Settings\Livewire\Users\Lists as UserLists;
use Modules\Settings\Livewire\Users\Show as UserShow;
use Modules\Settings\Livewire\Users\Create as UserCreate;
use Modules\Settings\Livewire\Companies\Lists as CompanyLists;
use Modules\Settings\Livewire\Companies\Create as CompanyCreate;
use Modules\Settings\Livewire\Companies\Show as CompanyShow;
use Modules\Settings\Livewire\Tasks\Lists as TaskLists;
use Modules\Settings\Livewire\Roles\Lists as RoleLists;

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

Route::middleware('identify-kover')->group( function () {
    Route::get('/settings', GeneralSetting::class)->name('settings.general');
    Route::get('/users', UserLists::class)->name('settings.users');
    Route::prefix('/users')->name('settings.users.')->group(function() {
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/{user}', UserShow::class)->name('show');
    });
    Route::prefix('/companies')->name('settings.companies.')->group(function() {
        Route::get('/', CompanyLists::class)->name('index');
        Route::get('create', CompanyCreate::class)->name('create');
        Route::get('/{company}', CompanyShow::class)->name('show');
    });
    // Tasks
    Route::get('/tasks', TaskLists::class)->name('tasks.lists');
    // Roles
    Route::get('/roles', RoleLists::class)->name('roles.lists');
});
