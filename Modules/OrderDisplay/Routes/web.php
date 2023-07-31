<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderDisplay\Http\Controllers\orderDisplayController;

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

Route::controller(orderDisplayController::class)->group(function () {
    // order
    Route::get('/order/display/list','kdList')->name('kdList');
    Route::get('/order/display/list/data','kdDataForList')->name('kdDataForList');
    Route::get('/order/display/create','kdCreate')->name('kdCreate');
    Route::post('/order/display/store','kdStore')->name('kdStore');


    Route::get('/order/{id}/display/edit','kdEdit')->name('kdEdit');
    Route::post('/order/{id}/display/update','kdUpdate')->name('kdUpdate');

    Route::delete('/order/display/destory','kdDestory')->name('kdDestory');
    Route::get('/order/display','kDisplay')->name('orderDisplay');
});
