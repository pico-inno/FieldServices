<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceTypeController;

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


//============================ Begin: Service ==============================================

// ====>    SERVICE TYPE
Route::controller(ServiceTypeController::class)->group(function () {
    Route::get('/service-type-datas', 'datas')->name('service-type.data');
    Route::get('/service-type', 'index')->name('service-type');
    Route::get('/service-type/add', 'add')->name('service-type.add');
    Route::post('/service-type/create', 'create')->name('service-type.create');
    Route::get('/service-type/edit/{serviceType}', 'edit')->name('service-type.edit');
    Route::put('/service-type/update/{serviceType}', 'update')->name('service-type.update');
    Route::delete('/service-type/delete/{serviceType}', 'delete')->name('service-type.delete');
});

// ====>    SERVICE
Route::controller(ServiceController::class)->group(function () {
    // ajax
    Route::get('/service/products', 'getProducts')->name('service.get-products');

    Route::get('/service-datas', 'datas')->name('service.data');
    Route::get('/service', 'index')->name('service');
    Route::get('/service/add', 'add')->name('service.add');
    Route::post('/service/create', 'create')->name('service.create');
    Route::get('/service/edit/{service}', 'edit')->name('service.edit');
    Route::put('/service/update/{service}', 'update')->name('service.update');
    Route::delete('/service/delete/{service}', 'delete')->name('service.delete');
});

// ====>    SERVICE SALES
Route::controller(ServiceSalesController::class)->group(function () {
    Route::get('/service-sale/service-product/{id}', 'getServiceProducts')->name('service-sale.service-product');

    Route::get('/service-sale-datas', 'datas')->name('service-sale.data');
    Route::get('/service-sale', 'index')->name('service-sale');
    Route::get('/service-sale/view/{serviceSale}', 'view')->name('service-sale.view');
    Route::get('/service-sale/add', 'add')->name('service-sale.add');
    Route::post('/service-sale/create', 'create')->name('service-sale.create');
    Route::get('/service-sale/edit/{serviceSale}', 'edit')->name('service-sale.edit');
    Route::put('/service-sale/update/{serviceSale}', 'update')->name('service-sale.update');
    Route::delete('/service-sale/delete/{serviceSale}', 'delete')->name('service-sale.delete');
});

//============================ End: Service ==============================================

