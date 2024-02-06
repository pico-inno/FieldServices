<?php

use Illuminate\Support\Facades\Route;
use Modules\Barcode\Http\Controllers\BarcodeController;

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


Route::resource('barcode', BarcodeController::class);

Route::controller(BarcodeController::class)->prefix('barcode')->group(function () {
    Route::get('/list/data','listData');

    Route::get('/prepare/page', 'prepare')->name('barcode.prepare');
    Route::post('/paper/print', 'print')->name('barcode.print');
});

