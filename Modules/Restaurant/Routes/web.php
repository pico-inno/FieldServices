<?php

use Illuminate\Support\Facades\Route;
use Modules\Restaurant\Http\Controllers\tableController;

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

Route::prefix('restaurant')->group(function() {
    Route::controller(tableController::class)->group(function () {
        // printer
        Route::get('/table/list','index')->name('restaurant.tableList');
        Route::get('/table/list/data','dataForList')->name('restaurant.dataForList');

        Route::get('/table/create','create')->name('restaurant.tableCreate');
        Route::post('/table/store','store')->name('restaurant.tableStore');


        Route::get('{id}/table/edit','edit')->name('restaurant.tableEdit');
        Route::post('{id}/table/update','update')->name('restaurant.tableUpdate');

        Route::delete('/table/destory','destory')->name('restaurant.destory');
        // Route::get('/pos/register/display','kDisplay')->name('restaurant.orderDisplay');
    });
});
