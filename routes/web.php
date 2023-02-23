<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Expenses\ExpensesTypeController;
use App\Http\Controllers\Expenses\ExpensesBillController;

use App\Http\Controllers\Business\BusinessCategoriesController;
use App\Http\Controllers\Business\BusinessSubcategoriesController;

use App\Http\Livewire\Expenses\ExpensesTypeLivewire;
use App\Http\Livewire\Expenses\ExpensesBillLivewire;

use App\Http\Livewire\Business\BusinessCategories;
use App\Http\Livewire\Business\BusinessSubCategories;
use App\Http\Livewire\Business\BusinessProducts;

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

Route::get('/',function(){
    return view('welcome');
});

Route::get('/expenses/type/livewire',ExpensesTypeLivewire::class)->name('expense.type.livewire');
Route::get('/expenses/bill/livewire',ExpensesBillLivewire::class)->name('expense.bill.livewire');
Route::get('/business/categories/livewire',BusinessCategories::class)->name('business.categories.livewire');
Route::get('/business/subcategories/livewire',BusinessSubCategories::class)->name('business.subcategories.livewire');
Route::get('/business/products/livewire',BusinessProducts::class)->name('business.products.livewire');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(ExpensesTypeController::class)->group(function () {
        Route::get('/expenses/type/all', 'index')->name('expenses.type.all');
        Route::post('/expenses/type/store', 'store')->name('expenses.type.store');
        Route::post('/expenses/type/status', 'status')->name('expenses.type.status');
        Route::get('/expenses/type/edit/{code}', 'edit')->name('expenses.type.edit');
        Route::post('/expenses/type/update/{code}', 'update')->name('expenses.type.update');
        Route::post('/expenses/type/delete/', 'destroy')->name('expenses.type.delete');
        Route::get('/expenses/type/active', 'active')->name('expenses.type.active');
    });
    Route::controller(ExpensesBillController::class)->group(function () {
        Route::get('/expenses/bills', 'index')->name('expenses.bills');
        Route::get('/expenses/bill/create', 'create')->name('expenses.bill.create');
        Route::post('/expenses/bill/store', 'store')->name('expenses.bill.store');
        Route::get('/expenses/bill/edit/{code}', 'edit')->name('expenses.bill.edit');
        
        Route::post('/expenses/bill/update/{code}', 'update')->name('expenses.bill.update');

        Route::post('/expenses/bill/delete/{code}', 'destroy')->name('expenses.bill.delete');
    });

    Route::controller(BusinessCategoriesController::class)->group(function(){
        Route::get('/business/categories/all','index')->name('business.categories');
        Route::post('/business/categories/store','store')->name('business.categories.store');
        Route::post('/business/categories/status','status')->name('business.categories.status');
        Route::get('/business/categories/edit/{code}','edit')->name('business.categories.edit');
        Route::post('/business/categories/edit/{code}','update');
        Route::post('/business/categories/delete','destroy')->name('business.categories.delete');
    });
    Route::controller(BusinessSubcategoriesController::class)->group(function(){
        Route::get('/business/subcategories/all','index')->name('business.subcategories.all');
        Route::get('/business/subcategories/create','create')->name('business.subcategories.create');
        Route::post('/business/subcategories/store','store')->name('business.subcategories.store');
        Route::post('/business/subcategories/status','status')->name('business.subcategories.status');
        Route::get('/business/subcategories/edit/{code}','edit')->name('business.subcategories.edit');
        Route::post('/business/subcategories/edit/{code}','update');
        Route::post('/business/subcategories/delete','destroy')->name('business.subcategories.delete');
    });
});
