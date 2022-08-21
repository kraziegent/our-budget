<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Budget;
use App\Http\Controllers\Categories;
use App\Http\Controllers\Category;
use App\Http\Controllers\Payee;
use App\Http\Controllers\Transaction;
use App\Http\Controllers\Transactions;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // User
    Route::controller(User::class)->prefix('user')->group(function() {
        Route::get('{user?}', 'show')->name('user.show');
        Route::put('{user?}', 'update')->name('user.update');
        Route::delete('{user?}', 'destroy')->name('user.destroy');
    });

    // Categories
    Route::controller(Category::class)->prefix('categories')->group(function () {
        Route::get('', 'index')->name('categories.index');
        Route::post('', 'store')->name('categories.store');
        Route::put('{category}', 'update')->name('categories.update');
        Route::delete('{category}', 'destroy')->name('categories.destroy');
    });

    Route::post('categories/store-many', Categories::class)->name('categories.store.many');

    // Accounts
    Route::controller(Account::class)->prefix('accounts')->group(function() {
        Route::get('', 'index')->name('accounts.index');
        Route::post('', 'store')->name('accounts.store');
        Route::put('{account}', 'update')->name('accounts.update');
        Route::delete('{account}', 'destroy')->name('accounts.destroy');
    });

    // Payees
    Route::controller(Payee::class)->prefix('payees')->group(function() {
        Route::get('', 'index')->name('payees.index');
        Route::post('', 'store')->name('payees.store');
        Route::put('{payee}', 'update')->name('payees.update');
        Route::delete('{payee}', 'destroy')->name('payees.destroy');
    });

    // Transactions
    Route::controller(Transaction::class)->prefix('transactions')->group(function() {
        Route::get('', 'index')->name('transactions.index');
        Route::post('', 'store')->name('transactions.store');
        Route::put('{transaction}', 'update')->name('transactions.update');
        Route::delete('{transaction}', 'destroy')->name('transactions.destroy');
    });

    Route::post('transactions/store-many', Transactions::class)->name('transactions.store.many');

    // Budgets
    Route::controller(Budget::class)->prefix('budgets')->group(function() {
        Route::get('', 'index')->name('budgets.index');
        Route::post('', 'store')->name('budgets.store');
        Route::post('{budget}/share', 'share')->name('budgets.share');
        Route::put('{budget}', 'update')->name('budgets.update');
        Route::delete('{budget}', 'destroy')->name('budgets.destroy');
    });
});
