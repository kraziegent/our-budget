<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Categories;
use App\Http\Controllers\Category;
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
});
