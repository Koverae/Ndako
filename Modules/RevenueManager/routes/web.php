<?php

use Illuminate\Support\Facades\Route;
use Modules\RevenueManager\Http\Controllers\RevenueManagerController;
use Modules\RevenueManager\Livewire\ExpenseCategory\Lists as ExpenseCategoryList;
use Modules\RevenueManager\Livewire\Expense\Lists as ExpenseList;
use Modules\RevenueManager\Livewire\Expense\Create as ExpenseCreate;
use Modules\RevenueManager\Livewire\Expense\Show as ExpenseShow;

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
    Route::resource('revenuemanager', RevenueManagerController::class)->names('revenuemanager');

    Route::get('/expenses/categories', ExpenseCategoryList::class)->name('expenses.categories.lists');
    // Route::get('/expenses', ExpenseList::class)->name('expenses.lists');
    
    // Expenses
    Route::prefix('/expenses')->name('expenses.')->group(function() {
        Route::get('/', ExpenseList::class)->name('lists');
        Route::get('/create', ExpenseCreate::class)->name('create');
        Route::get('/{expense}', ExpenseShow::class)->name('show');

    });
});
