<?php

use Illuminate\Support\Facades\Route;
use Modules\StockInOut\Http\Controllers\StockInController;
use Modules\StockInOut\Http\Controllers\StockInOutReportController;
use Modules\StockInOut\Http\Controllers\StockOutController;

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

//Route::prefix('stockinout')->group(function() {
//    Route::get('/', 'StockInOutController@index');
//});

Route::resource('stock-in', StockInController::class);
Route::get('stockin/upcoming', [StockInController::class, 'upcoming'])->name('stock-in.upcoming.index');
Route::get('stock-in/receive/{stock_in}', [StockInController::class, 'receiveProduct'])->name('stock-in.receive');
Route::post('stockin/search/product', [StockInController::class, 'searchProduct']);
Route::post('stockin/filter-supplier', [StockInController::class, 'filterSupplier']);
Route::post('stockin/filter-purchase-order', [StockInController::class, 'filterPurchaseOrder']);
Route::post('stockin/filter-purchase-order/data', [StockInController::class, 'filterPurchaseOrderData']);
Route::post('/stockin/filter-list', [StockInController::class, 'filterList']);
Route::get('stock-in/{id}/invoice-print',[StockInController::class, 'stockinInvoicePrint'])->name('stock-in.invoice.print');
Route::resource('stock-in', StockInController::class)->except('destroy');
Route::delete('stockin/{id}/delete', [StockInController::class, 'softDelete']);


Route::resource('stock-out', StockOutController::class);
Route::get('stockout/outgoing', [StockOutController::class, 'outgoing'])->name('stock-out.outgoing.index');
Route::get('stockout/dispensed/{stock_out}', [StockOutController::class, 'deliveredProduct'])->name('stock-out.delivered');
Route::post('stockout/filter-customer', [StockOutController::class, 'filterCustomer']);
Route::post('/stockout/filter-list', [StockOutController::class, 'filterList']);
Route::post('stockout/filter-sale-order', [StockOutController::class, 'filterSaleOrder']);
Route::post('/stockout/filter-sale-order/data', [StockOutController::class, 'filterSaleOrderData']);
Route::get('stock-out/{id}/invoice-print',[StockOutController::class, 'stockoutInvoicePrint'])->name('stock-out.invoice.print');
Route::resource('stock-out', StockOutController::class)->except('destroy');
Route::delete('stockout/{id}/delete', [StockOutController::class, 'softDelete']);

Route::controller(StockInOutReportController::class)->group(function () {
    Route::prefix('reports')->group(function () {
        //=================================Being: Inventory Reports ========================
        //Stock in/out summary
        Route::get('/stock-report','stock_index')->name('report.stock.index');
        Route::post('stock-report/filter-list', 'stockFilter');
        //Stock in/out  details
        Route::get('/stock-details-report','stock_details_index')->name('report.stock.details.index');
        Route::post('stock-details/filter-list', 'stockDetailsFilter');
    });

});
