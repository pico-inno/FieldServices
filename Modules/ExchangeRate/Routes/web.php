<?php

use Illuminate\Support\Facades\Route;
use Modules\ExchangeRate\Http\Controllers\exchangeRateController;

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

Route::prefix('exchange-rate')->group(function () {
    Route::controller(exchangeRateController::class)->group(function () {
        Route::get('/list','index')->name('exchangeRate.list');
        Route::get('/create','create')->name('exchangeRate.create');
        Route::post('/store','store')->name('exchangeRate.store');
        Route::get('{id}/edit','edit')->name('exchangeRate.edit');
        Route::post('{id}/update','update')->name('exchangeRate.update');
        Route::delete('/destory','destory')->name('exchangeRate.destory');
        Route::get('/get/list/','list');
    });
});
