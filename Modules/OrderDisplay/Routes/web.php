<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderDisplay\Http\Controllers\orderDisplayController;
use Modules\OrderDisplay\Http\Controllers\ResOrderController;

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
    Route::get('/order/display/list','odList')->name('odList');
    Route::get('/order/display/list/data','odDataForList')->name('odDataForList');
    Route::get('/order/display/create','odCreate')->name('odCreate');
    Route::post('/order/display/store','odStore')->name('odStore');


    Route::get('/order/{id}/display/edit','odEdit')->name('odEdit');
    Route::post('/order/{id}/display/update','odUpdate')->name('odUpdate');

    Route::delete('/order/display/destory','odDestory')->name('odDestory');
    Route::get('/order/display','odDisplay')->name('orderDisplay');


});

Route::controller(ResOrderController::class)->group(function(){
    Route::get('/res/order/data','restOrderData')->name('odDisplayData');
});
