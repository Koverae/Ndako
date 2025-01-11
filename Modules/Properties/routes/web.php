<?php

use Illuminate\Support\Facades\Route;
use Modules\Properties\Http\Controllers\PropertiesController;
use Modules\Properties\Livewire\Overview;
use Modules\Properties\Livewire\Properties\Lists as PropertyLists;
use Modules\Properties\Livewire\Properties\Create as PropertyCreate;
use Modules\Properties\Livewire\Properties\Show as PropertyShow;
use Modules\Properties\Livewire\Units\Lists as UnitLists;
use Modules\Properties\Livewire\Units\Create as UnitCreate;
use Modules\Properties\Livewire\Units\Show as UnitShow;
use Modules\Properties\Livewire\UnitTypes\Lists as UnitTypeLists;
use Modules\Properties\Livewire\UnitTypes\Create as UnitTypeCreate;
use Modules\Properties\Livewire\UnitTypes\Show as UnitTypeShow;
use Modules\Properties\Livewire\PropertyType\Lists as PropertyTypeLists;
use Modules\Properties\Livewire\PropertyType\Create as PropertyTypeCreate;
use Modules\Properties\Livewire\PropertyType\Show as PropertyTypeShow;

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
    Route::get('properties/overview', Overview::class)->name('properties.index');
    // Properties
    Route::get('properties', PropertyLists::class)->name('properties.lists');
    Route::prefix('/properties')->name('properties.')->group(function() {
        Route::get('/create', PropertyCreate::class)->name('create');
        Route::get('/{property}', PropertyShow::class)->name('show');
    });
    // Property Types
    Route::prefix('/property-types')->name('properties.types.')->group(function() {
        Route::get('/', PropertyTypeLists::class)->name('lists');
        Route::get('/create', PropertyTypeCreate::class)->name('create');
        Route::get('/{type}', PropertyTypeShow::class)->name('show');
        
    });
    // Unit Types
    Route::prefix('/unit-types')->name('properties.unit-types.')->group(function() {
        Route::get('/', UnitTypeLists::class)->name('lists');
        Route::get('/create', UnitTypeCreate::class)->name('create');
        Route::get('/{type}', UnitTypeShow::class)->name('show');
        
    });
    // Units
    Route::prefix('/units')->name('properties.units.')->group(function() {
        Route::get('/', UnitLists::class)->name('lists');
        Route::get('/create', UnitCreate::class)->name('create');
        Route::get('/{unit}', UnitShow::class)->name('show');

    });
});
