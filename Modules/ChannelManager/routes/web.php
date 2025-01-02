<?php

use Illuminate\Support\Facades\Route;
use Modules\ChannelManager\Http\Controllers\ChannelManagerController;
use Modules\ChannelManager\Livewire\Channels\Lists as ChannelLists;
use Modules\ChannelManager\Livewire\Channels\Show as ChannelShow;
use Modules\ChannelManager\Livewire\Overview;

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
    // Route::get('channelmanager', ChannelManagerController::class, 'index')->name('channels.index');
    Route::get('channels/overview', Overview::class)->name('channels.index');
    Route::get('channels', ChannelLists::class)->name('channels.lists');
    Route::get('channels/{channel}', ChannelShow::class)->name('channels.show');
});
