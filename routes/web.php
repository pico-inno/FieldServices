<?php

use App\Models\Manufacturer;
use Illuminate\Http\Request;

use App\Models\Product\UOMSet;
use App\Services\mailServices;
use App\Models\Contact\Contact;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CurrentStockBalance;
use App\Models\purchases\purchases;
use Illuminate\Support\Facades\App;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\openingStocks\Import;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\mailController;
use App\Http\Controllers\TestController;
use App\Models\Product\PriceListDetails;
use App\Http\Controllers\tableController;
use App\Http\Middleware\businessActivate;
use App\Models\settings\businessSettings;
use App\Models\purchases\purchase_details;
use App\Http\Controllers\expenseController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\printerController;
use App\Http\Controllers\currencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\sell\saleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\restaurantController;
use App\Http\Middleware\permissions\role\view;
use App\Http\Controllers\Product\UoMController;
use App\Http\Controllers\exchangeRateController;
// use App\Http\Controllers\ContactController\CustomerGroupController;
// use App\Http\Controllers\ContactController\ImportContactsController;
use App\Http\Controllers\openingStockController;
use App\Http\Controllers\orderDisplayController;
use App\Http\Controllers\Product\UnitController;
use App\Http\Controllers\stockHistoryController;
//use App\Http\Controllers\Stock\StockTransferController;
use App\Http\Controllers\expenseReportController;
use App\Http\Controllers\module\moduleController;
use App\Http\Controllers\Product\BrandController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Stock\StockInController;
use App\Http\Controllers\settings\FloorController;
use App\Http\Controllers\Stock\StockOutController;
use App\Http\Controllers\deliveryChannelController;
use App\Http\Controllers\paymentAccountsController;
use App\Http\Controllers\posRegistrationController;
use App\Http\Controllers\Product\GenericController;
// use App\Http\Controllers\Product\PriceGroupController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Contact\CustomerController;
use App\Http\Controllers\Contact\SupplierController;
use App\Http\Controllers\Product\CategoryController;
use App\Http\Controllers\Product\PriceListController;
use App\Http\Controllers\Product\VariationController;
use App\Http\Controllers\purchase\purchaseController;
use App\Http\Controllers\settings\BuildingController;
use App\Http\Controllers\businessActivationController;
use App\Http\Controllers\import\importOpeningStockController;
use App\Http\Controllers\Service\ServiceTypeController;
use App\Http\Controllers\Stock\StockTransferController;
use \App\Http\Controllers\userManagement\RoleController;
use App\Http\Controllers\paymentsTransactionsController;
use App\Http\Controllers\Product\ManufacturerController;
use App\Http\Controllers\Product\UnitCategoryController;
use App\Http\Controllers\Service\ServiceSalesController;
use App\Http\Controllers\Contact\CustomerGroupController;
use App\Http\Controllers\hospitalFolioInvoicesController;
use App\Http\Controllers\hospitalRegistrationsController;
use App\Http\Controllers\posSession\posSessionController;
use App\Http\Controllers\Product\ImportProductController;
use App\Http\Controllers\Stock\StockAdjustmentController;
use App\Http\Controllers\Contact\ImportContactsController;
use App\Http\Controllers\configurationController;
use App\Http\Controllers\export\ExportController;
use App\Http\Controllers\import\priceListImportController;
use App\Http\Controllers\languageController;
use App\Http\Controllers\Product\PriceListDetailController;
use App\Http\Controllers\settings\businessSettingController;
use App\Http\Controllers\settings\businessLocationController;
use App\Http\Controllers\settings\bussinessSettingController;
use App\Http\Controllers\userManagement\UserProfileController;
use App\Http\Controllers\userManagement\users\BusinessUserController;

// use App\Models\Manufacturer;

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

Route::get('/create/business', [businessActivationController::class, 'activationForm'])->name('activationForm')->middleware('businessActivate');
Route::post('/store/business', [businessActivationController::class, 'store'])->name('businessActivation.store')->middleware('businessActivate');
Route::get('/install', [configurationController::class, 'envConfigure'])->name('envConfigure')->middleware('install');
Route::post('/intall/store', [configurationController::class, 'store'])->name('envConfigure.store')->middleware('install');
Route::get('/migration/form', [configurationController::class, 'migrationForm'])->name('envConfigure.migrationForm');
Route::get('/migration/form', [configurationController::class, 'migrationForm'])->name('envConfigure.migrationForm');
Route::post('/data/seed/', [configurationController::class, 'dataSeed'])->name('envConfigure.dataSeed');
// Route::get('/', function () {
//     return view('App.dashboard');
// });



//============================ Being: User Management ==========================================
Auth::routes();

//_Being: Auth
Route::get('/', [LoginController::class, 'showLoginForm']);
//_End: Auth

//Being: Dashboard
Route::controller(DashboardController::class)->group(function () {
    Route::get('/home', 'index')->name('home');

    //Filter
    Route::post('/dashboard/current-balance-filter', 'currentBalanceFilter');
    Route::post('/dashboard/total-current-qty', 'totalCurrentBalanceQty');
    Route::post('/dashboard/total-contacts-widget', 'totalContact');
    Route::post('/dashboard/total-sale-purchase-order-widget', 'totalSaleAndPurchaseOrder');
});
//End: Dashboard
Route::get('lang/{code}',[languageController::class, 'change'])->name('lang.change');
//_Being: Users
Route::resource('users', BusinessUserController::class);
//_End: Users

//_Being: Roles
Route::resource('roles', RoleController::class);
//_End: Roles

//_Being: User Profile
Route::controller(UserProfileController::class)->group(function () {
    Route::get('profile', 'index')->name('profile.index');
    Route::get('profile/settings', 'settings')->name('profile.settings');
    Route::put('profile/settings_info/{id}', 'update_info')->name('profile.info.update');
    Route::put('profile/settings_email/{id}', 'update_email')->name('profile.email.update');
    Route::put('profile/settings_password/{id}', 'update_password')->name('profile.password.update');
    Route::put('profile/settings_deactivate/{id}', 'deactivate')->name('profile.deactivate');
});
//_End: User Profile

//============================ End: User Management ============================================

//============================ Being: Stock - In, Out, Transfer, Adjustment ===========================================

//_Being: Stock In
//Route::resource('stock-in', StockInController::class);
//Route::get('stockin/upcoming', [StockInController::class, 'upcoming'])->name('stock-in.upcoming.index');
//Route::get('stock-in/receive/{stock_in}', [StockInController::class, 'receiveProduct'])->name('stock-in.receive');
//Route::post('stockin/search/product', [StockInController::class, 'searchProduct']);
//Route::post('stockin/filter-supplier', [StockInController::class, 'filterSupplier']);
//Route::post('stockin/filter-purchase-order', [StockInController::class, 'filterPurchaseOrder']);
//Route::post('stockin/filter-purchase-order/data', [StockInController::class, 'filterPurchaseOrderData']);
//Route::post('/stockin/filter-list', [StockInController::class, 'filterList']);
//Route::get('stock-in/{id}/invoice-print',[StockInController::class, 'stockinInvoicePrint'])->name('stock-in.invoice.print');
//Route::resource('stock-in', StockInController::class)->except('destroy');
//Route::delete('stockin/{id}/delete', [StockInController::class, 'softDelete']);
//_End: Stock In

//_Being: Stock Out
//Route::resource('stock-out', StockOutController::class);
//Route::get('stockout/outgoing', [StockOutController::class, 'outgoing'])->name('stock-out.outgoing.index');
//Route::get('stockout/dispensed/{stock_out}', [StockOutController::class, 'deliveredProduct'])->name('stock-out.delivered');
//Route::post('stockout/filter-customer', [StockOutController::class, 'filterCustomer']);
//Route::post('/stockout/filter-list', [StockOutController::class, 'filterList']);
//Route::post('stockout/filter-sale-order', [StockOutController::class, 'filterSaleOrder']);
//Route::post('/stockout/filter-sale-order/data', [StockOutController::class, 'filterSaleOrderData']);
//Route::get('stock-out/{id}/invoice-print',[StockOutController::class, 'stockoutInvoicePrint'])->name('stock-out.invoice.print');
//Route::resource('stock-out', StockOutController::class)->except('destroy');
//Route::delete('stockout/{id}/delete', [StockOutController::class, 'softDelete']);
//_End: Stock Out

//_Being: Stock Transfer
Route::resource('stock-transfer', StockTransferController::class);
Route::post('stock-transfer/filter-list', [StockTransferController::class, 'filterList']);
Route::get('stock-transfer/{id}/invoice-print', [StockTransferController::class, 'stocktransferInvoicePrint'])->name('stock-transfer.invoice.print');
Route::resource('stock-transfer', StockTransferController::class)->except('destroy');
Route::delete('stock-transfer/{id}/delete', [StockTransferController::class, 'softDelete']);
Route::get('/transfer/tableData', [StockTransferController::class, 'listData']);
Route::get('transfer/print/{id}/invoice', [StockTransferController::class, 'invoicePrint'])->name('transfer.print');
//_End: Stock Transfer


//_Being: Stock Adjustment
Route::resource('stock-adjustment', StockAdjustmentController::class);
Route::post('stock-adjustment/filter-list', [StockAdjustmentController::class, 'filterList']);
Route::resource('stock-adjustment', StockAdjustmentController::class)->except('destroy');
Route::delete('stock-adjustment/{id}/delete', [StockAdjustmentController::class, 'softDelete']);
Route::get('/adjustment/tableData', [StockAdjustmentController::class, 'listData']);
Route::get('adjustment/print/{id}/invoice', [StockAdjustmentController::class, 'invoicePrint'])->name('adjustment.print');
//_End: Stock Adjustment

//Route::get('/stock/{id}/units', [StockInController::class, 'getUnits']);
//Route::get('/stock/{unit_id}/{uom_id}/values', [StockInController::class, 'getUOMVal']);
Route::post('/stock/get-product', [StockTransferController::class, 'getProduct']);
Route::post('/stock/get-product-edit', [StockTransferController::class, 'editGetProduct']);
Route::get('/stock/get-stock', [StockTransferController::class, 'getStock']);
Route::get('/stock/get-current-qty', [StockTransferController::class, 'getCurrentQtyOnUnit']);



//============================ End: Stock - In, Out, Transfer, Adustment ===========================================


//============================ Being: Reports ===========================================
Route::controller(ReportController::class)->group(function () {
    Route::prefix('reports')->group(function () {
        //=================================Being: Inventory Reports ========================
        //Stock in/out summary
        //        Route::get('/stock-report','stock_index')->name('report.stock.index');
        //        Route::post('stock-report/filter-list', 'stockFilter');
        //Stock in/out  details
        //        Route::get('/stock-details-report','stock_details_index')->name('report.stock.details.index');
        //        Route::post('stock-details/filter-list', 'stockDetailsFilter');

        //Being: Sale
        Route::get('/sales', 'saleIndex')->name('report.sale.index');
        Route::post('/sales/filter-list', 'saleFilter');
        Route::post('/sales/this-months', 'saleFilterThisMonths');
        Route::get('/sales-details', 'saleDetailsIndex')->name('report.sale.details.index');
        Route::post('/sale-details/filter-list', 'saleDetailsFilter');
        //End: Sale

        //Being: Purchase
        Route::get('/purchase', 'purchaseIndex')->name('report.purchase.index');
        Route::post('/purchase/filter-list', 'purchaseFilter');
        Route::get('/purchase-details', 'purchaseDetailsIndex')->name('report.purchase.details.index');
        Route::post('/purchase-details/filter-list', 'purchaseDetailsFilter');
        //End: Purchase

        //Being: Qty Alert
        Route::get('/alert-quantity', 'quantityAlert')->name('report.stockAlert.quantity');
        Route::post('/alert-quantity/filter-list', 'quantityAlertFilter');
        //End: Qty Alert

        //Being: Expire Alert
        Route::get('/alert-expire', 'expireAlert')->name('report.stockAlert.expire');
        Route::post('/alert-expire/filter-list', 'expireAlertFilter');
        //End: Expire Alert



        //Stock transfer summary
        Route::get('/stock-transfer-report', 'stock_transfer_index')->name('report.stocktransfer.index');
        Route::post('/transfer-report/filter-list', 'transferFilter');
        //Stock transfer details
        Route::get('/transfer-details-report', 'transfer_details_index')->name('report.transfer.details.index');
        Route::post('/transfer-details/filter-list', 'transferDetailsFilter');

        //Current Stock Balance
        Route::get('/current-stock-balance', 'currentStockBalanceIndex')->name('report.currentstockbalance.index');
        Route::post('/current-stock-balance/filter-list', 'currentStockBalanceFilter');

        //=================================End: Inventory Reports ========================


    });
});
//============================ End: Reports ===========================================


//============================ Being: Business Settings ==========================================


//============================ Being: Reports ===========================================
//Route::controller(ReportController::class)->group(function () {
//    Route::prefix('reports')->group(function () {
//        Route::get('/stock-report','stock_index')->name('report.stock.index');
//        Route::get('/stock-transfer-report','stock_transfer_index')->name('report.stocktransfer.index');
//    });
//
//});
//============================ End: Reports ===========================================


Route::controller(businessSettingController::class)->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('/business', 'index')->name('business_settings');
        Route::post('update/business', 'update')->name('business_settings_update');
        Route::post('create/business', 'create')->name('business_settings_create');
    });
});

Route::controller(businessLocationController::class)->group(function () {
    Route::prefix('location')->group(function () {
        Route::get('/view', 'index')->name('business_location');

        // ajax list
        Route::get('/data', 'listData');
        // ajax delete
        Route::delete('{id}/delete', 'destory');
        Route::delete('/delete', 'destorySelected');

        // ajax deactive
        Route::delete('{id}/deactive', 'deactive');
        // ajax active
        Route::delete('{id}/active', 'active');
        // add
        Route::get('/add/form', 'addForm')->name('location_add_form');
        Route::post('/add', 'add')->name('location_add');


        Route::get('{businessLocation}/view', 'view')->name('location_view');
        // update
        Route::get('/update/{businessLocation}/form', 'updateForm')->name('location_update_form');
        Route::post('{id}/update', 'update')->name('location_update');
    });
});


Route::get('/location/setting', function () {
    return view('App.businessSetting.location.setting');
});



Route::resource('building', BuildingController::class);
Route::resource('floor', FloorController::class);


//============================ End::Business Settings ============================================


//============================ Being: Purchase  ==========================================


Route::prefix('purchase')->group(function () {
    Route::controller(purchaseController::class)->group(function () {
        Route::get('/list', 'index')->name('purchase_list');
        Route::get('/list/data', 'listData');

        Route::get('/add', 'add')->name('purchase_add');
        Route::get('/new/add', 'purchase_new_add')->name('purchase_new_add');
        Route::post('/store', 'store')->name('purchase_store');

        Route::get('{id}/edit', 'edit')->name('purchase_edit');
        Route::post('{id}/update', 'update')->name('purchase_update');
        //invoice
        Route::get('print/{id}/Invoice', 'purhcaseInvoice')->name('print_purchase');
        Route::get('view/{id}/detail', 'purchaseDetail')->name('purchaseDetail');

        Route::delete('{id}/softDelete', 'softOneItemDelete');
        Route::delete('softDelete', 'softSelectedDelete');


        Route::get('{id}/units', 'getUnits');
        Route::get('/get/product', 'getProductForPurchase');
        Route::get('/get/product/v2', 'getProductForPurchaseV2');
    });
});

Route::get('purchase/order', fn () => view('App.purchase.purchaseOrder'))->name('purchase_order');
// Route::get('purchase/add', fn () => view('App.purchase.addPurchase'))->name('purchase_add');
Route::get('purchase/add/order', fn () => view('App.purchase.purchaseOrderAdd'))->name('purchase_order_add');
Route::get('purchase/add/supplier', fn () => view('App.purchase.supplierAdd'))->name('purchase_supplier_add');
// Route::get('purchase/list', fn () => view('App.purchase.listPurchase'))->name('purchase_list');
Route::get('purchase/list/return', fn () => view('App.purchase.listPurchaseReturn'))->name('purchase_list_return');
Route::get('purchase/list/return/add', fn () => view('App.purchase.addListPurchaseReturn'))->name('add_purchase_list_return');

//============================ End::purchase ============================================

//============================ Being: Sale  ==========================================
Route::prefix('sell')->group(function () {
    Route::controller(saleController::class)->group(function () {
        Route::get('{saleType?}/sales', 'index')->name('all_sales');
        Route::get('view/{id}/detail', 'saleDetail')->name('saleDetail');
        Route::get('get/saleItem', 'saleItemsList');
        Route::get('create/page/', 'createPage')->name('add_sale');

        Route::get('{id}/edit', 'saleEdit')->name('saleEdit');

        Route::post('create/', 'store')->name('crate_sale');
        Route::post('/${id}/update', 'update')->name('update_sale');

        Route::get('get/product', 'getProduct');

        Route::get('get/product/v2', 'getProductV2');
        Route::get('get/suggestion/product', 'getSuggestionProduct');
        Route::get('{id}/price/list', 'getpriceList');



        //invoice
        Route::get('print/{id}/Invoice', 'saleInvoice')->name('print_sale');
        Route::delete('{id}/delete', 'softDelete');
        Route::delete('deletee/selected', 'softSelectedDelete');



        Route::get('/registration/post/{id}/Folio', 'postToRegistrationFolio')->name('postToRegistrationFolio');
        Route::post('/registration/post/Folio', 'addToRegistrationFolio')->name('addToRegistrationFolio');


        Route::post('/split/', 'saleSplitForPos')->name('saleSplitForPos');
    });
});

Route::get('sell/order', fn () => view('App.sell.sale.saleOrder'))->name('sale_order');
// Route::get('add/sell', fn () => view('App.sell.sale.addSale'))->name('add_sale');
Route::get('add/sell/order', fn () => view('App.sell.sale.addSaleOrder'))->name('add_sale_order');
Route::get('sell/list/pos', fn () => view('App.sell.pos.listPos'))->name('list_pos');

Route::get('sell/list/drafts', fn () => view('App.sell.draft.listDraft'))->name('list_drafts');
Route::get('sell/drafts/add', fn () => view('App.sell.draft.addDraft'))->name('add_draft');

Route::get('sell/list/quotations', fn () => view('App.sell.quotations.list'))->name('list_quotations');
Route::get('sell/quotation/add', fn () => view('App.sell.quotations.add'))->name('add_quotations');

Route::get('sell/shipments', fn () => view('App.sell.shipments'))->name('shipments');


//============================ End::Sale ============================================


//============================ Start::Opening Stock ============================================




Route::controller(openingStockController::class)->group(function () {
    Route::get('/openingStock/list', 'index')->name('opening_stock_list');
    Route::get('/openingStock/list/data', 'OpeningStockData');

    Route::get('view/{id}/openingStock/', 'view')->name('view_opening_stock');

    Route::get('print/{id}/invoice/', 'printInvoice')->name('printInvoice');

    Route::get('add/openingStock/', 'add')->name('add_opening_stock');
    Route::post('add/openingStock/', 'store')->name('store_opening_stock');

    Route::get('edit/{id}/openingStock/', 'edit')->name('openingStockEdit');
    Route::post('edit/{id}/openingStock/', 'update')->name('openingStockUpdate');


    Route::get('import/openingStock/', 'import')->name('import_opening_stock');
    Route::delete('/delete/{id}/opening/stock', 'softOneItemDelete');
    Route::delete('/all/delete/opening/stock', 'softSelectedDelete');
});

Route::controller(importOpeningStockController::class)->group(function () {
    Route::post('/opening/import', 'import')->name('importOpeningStock');
    Route::get('/download/demo/excel', 'dowloadDemoExcel')->name('download-excel');
});
Route::controller(ExportController::class)->group(function () {
    Route::get('/opening/export/product', 'export')->name('exprotOpeningStockWithProduct');
    Route::get('/product/export/productlist', 'productListExport')->name('export-productlist');
    Route::get('/price-list/{id}/export/edit-data', 'priceListExport')->name('export-priceList');
    Route::get('/price-list/export/data', 'priceListExportWithData')->name('export-priceListWithData');
});
//============================ End::Opening Stock ============================================
Route::prefix('stock-history')->group(function () {
    Route::controller(stockHistoryController::class)->group(function () {
        Route::get('/list', 'index')->name('stockHistory_list');
        Route::get('/get/list/', 'historyList');
    });
});



//============================ start::exchange rate ============================================

Route::prefix('deliver-channel')->group(function () {
    Route::controller(deliveryChannelController::class)->group(function () {
        Route::get('/list', 'index')->name('deliveryChannel.list');
        // Route::get('/create', 'create')->name('paymentAcc.create');
        // Route::post('/store', 'store')->name('paymentAcc.store');

        // Route::get('{id}/edit', 'edit')->name('paymentAcc.edit');
        // Route::post('{id}/update', 'update')->name('paymentAcc.update');

        // Route::get('{id}/view', 'view')->name('paymentAcc.view');
        // Route::delete('/destory', 'destory')->name('paymentAcc.destory');

        // Route::get('/get/list/', 'list');
        // Route::get('/get/{currency_id}', 'getByCurrency')->name('paymetAcc.getByCurrency');
    });
});
Route::prefix('SMS/{service}')->group(function () {
    Route::controller(SMSController::class)->group(function () {
        Route::get('dashboard', 'index')->name('sms.index');
        Route::get('get/sms', 'getSMS')->name('sms.getsms');
        Route::get('create', 'create')->name('sms.create');
        Route::post('send', 'send')->name('sms.send');
    });
});
Route::prefix('mail')->group(function () {
    Route::controller(mailController::class)->group(function () {
        Route::get('compose', 'commpose')->name('mail.compose');
        Route::post('send', 'send')->name('mail.send');
    });
});
Route::get('/sendMail', [mailServices::class, 'sendEmail']);

Route::prefix('payment-account')->group(function () {
    Route::controller(paymentAccountsController::class)->group(function () {
        Route::get('/list', 'index')->name('paymentAcc.list');
        Route::get('/create', 'create')->name('paymentAcc.create');
        Route::post('/store', 'store')->name('paymentAcc.store');

        Route::get('{id}/edit', 'edit')->name('paymentAcc.edit');
        Route::post('{id}/update', 'update')->name('paymentAcc.update');

        Route::get('{id}/view', 'view')->name('paymentAcc.view');
        Route::delete('/destory', 'destory')->name('paymentAcc.destory');

        Route::get('/get/list/', 'list');
        Route::get('/get/{currency_id}', 'getByCurrency')->name('paymetAcc.getByCurrency');
    });
});
Route::prefix('currency')->group(function () {
    Route::controller(currencyController::class)->group(function () {
        Route::get('/get/{id}/payment-account', 'paymentAccountByCurrency');
    });
});

Route::prefix('payment-transactions')->group(function () {
    Route::controller(paymentsTransactionsController::class)->group(function () {
        Route::get('{id}/get/list', 'list')->name('paymentTransaction.list');

        // withdrawl
        Route::get('{id}/withdrawl', 'withdrawl')->name('paymentTransaction.withdrawl');
        Route::post('{id}/withdrawl', 'storeWithdrawl')->name('paymentTransaction.storeWithdrawl');

        Route::get('{id}/transfer', 'transferUi')->name('paymentTransaction.transfer');
        Route::post('{id}/transfer', 'makeTransfer')->name('paymentTransaction.makeTransfer');

        // deposite
        Route::get('{id}/deposit', 'deposit')->name('paymentTransaction.deposit');
        Route::post('{id}/deposit', 'depositStore')->name('paymentTransaction.depositStore');

        // crud
        Route::post('/store', 'store')->name('paymentTransaction.store');
        Route::get('{id}/edit', 'edit')->name('paymentTransaction.edit');

        Route::post('{id}/update', 'update')->name('paymentTransaction.update');
        Route::get('{id}/view', 'view')->name('paymentTransaction.view');
        Route::delete('/destory', 'destory')->name('paymentTransaction.destory');


        // ===================================================== Create & Store =======================================================
        // expense
        Route::get('/create/{id}/expense/', 'createForExpense')->name('paymentTransaction.createForExpense');
        Route::post('/store/{id}/expense/report', 'storeForExpense')->name('paymentTransaction.storeForExpense');
        //purchase
        Route::get('/create/{id}/purchase/', 'createForPurchase')->name('paymentTransaction.createForPurchase');
        Route::post('/store/{id}/purchase', 'storeForPurchase')->name('paymentTransaction.storeForPurchase');
        //Sale
        Route::get('/create/{id}/sale/', 'createForSale')->name('paymentTransaction.createForSale');
        Route::post('/store/{id}/sale', 'storeForSale')->name('paymentTransaction.storeForSale');



        // ===================================================== View =======================================================
        // expense
        Route::get('/view/{id}/expense/report', 'viewForExpense')->name('paymentTransaction.viewForExpense');
        // purchase
        Route::get('/view/{id}/purchase', 'viewForPurchase')->name('paymentTransaction.viewForPurchase');
        // sell
        Route::get('/view/{id}/sell', 'viewForSale')->name('paymentTransaction.viewForSell');


        // ===================================================== Edit & Update =======================================================
        //expense
        Route::get('/edit/{id}/expense/report', 'editForExpense')->name('paymentTransaction.editForExpense');
        Route::post('/update/{id}/{transaction_type}/report', 'updatetTransaction')->name('paymentTransaction.updatetForExpense');
        //purchase
        Route::get('/edit/{id}/purchase/', 'editForPurchase')->name('paymentTransaction.editForPurchase');
        Route::post('/update/{id}/{transaction_type}/purchase', 'updatetTransaction')->name('paymentTransaction.updatetForPurchase');
        //sale
        Route::get('/edit/{id}/sale/', 'editForSale')->name('paymentTransaction.editForSale');
        Route::post('/update/{id}/{transaction_type}/sale', 'updatetTransaction')->name('paymentTransaction.updatetForSale');

        // ===================================================== Remove =======================================================
        Route::delete('/remove/{id}/expense/report/', 'removeForExpense')->name('paymentTransaction.removeForExpense');
        Route::delete('/remove/{id}/sale/', 'removeForSale')->name('paymentTransaction.removeForSale');
        Route::delete('/remove/{id}/purchase/', 'removeForPurchase')->name('paymentTransaction.removeForPurchase');
    });
});


Route::prefix('expense')->group(function () {
    Route::controller(expenseController::class)->group(function () {
        Route::get('/create/', 'create')->name('expense.create');

        Route::post('/create/', 'store')->name('expense.store');

        Route::get('{id}/edit/', 'edit')->name('expense.edit');
        Route::post('{id}/update/', 'update')->name('expense.update');
        Route::post('/update/report', 'updateFromReport')->name('expense.updateFromReport');

        Route::delete('/destory', 'destory');

        Route::get('/product', 'expenseProduct')->name('expense.product');
        Route::get('/product/create', 'productCreate')->name('expense.productCreate');

        Route::get('{id}/view/', 'view')->name('expense.view');

        Route::get('/list/', 'index')->name('expense.list');
        Route::get('/list/data', 'dataForList')->name('expense.listData');
    });
});
//============================ End::exchange Rate ============================================
Route::prefix('expense-report')->group(function () {
    Route::controller(expenseReportController::class)->group(function () {
        // Route::get('/list/','index')->name('expenseReport.list');

        Route::get('/list', 'index')->name('expenseReport.list');
        Route::get('/list/data', 'dataForList')->name('expenseReport.listData');

        Route::get('/create', 'create')->name('expenseReport.create');
        Route::post('/store', 'store')->name('expenseReport.store');


        Route::get('{id}/edit', 'edit')->name('expenseReport.edit');
        Route::post('{id}/update', 'update')->name('expenseReport.update');

        Route::get('{id}/view/', 'view')->name('expenseReport.view');

        Route::get('{id}/list/expense', 'expenseData')->name('expenseReport.expenseData');

        Route::delete('/destory', 'destory');

        Route::delete('{id}/remove/', 'removeFromReport')->name('expenseReport.removeFromReport');


        Route::post('/paid/all', 'paidAll')->name('expenseReport.paidAll');
    });
});

//============================ start::stock history ============================================







// Route::prefix('restaurant')->group(function () {


// });


Route::controller(posRegistrationController::class)->group(function () {
    // pos
    Route::get('/pos/register/list', 'list')->name('posList');
    Route::get('/pos/register/data', 'dataForList')->name('posDataForList');
    Route::get('/pos/register/create', 'create')->name('posCreate');
    Route::post('/pos/register/store', 'store')->name('posStore');


    Route::get('/pos/register/{id}/edit', 'edit')->name('posEdit');
    Route::post('/pos/register/{id}/update', 'update')->name('posUpdate');
    Route::delete('/pos/register/destory', 'destory')->name('posDestory');
});
Route::controller(printerController::class)->group(function () {
    // printer
    Route::get('/printer/list', 'index')->name('printerList');
    Route::get('/printer/list/data', 'DataForList')->name('DataForList');
    Route::get('/printer/create', 'create')->name('printerCreate');
    Route::post('/printer/store', 'store')->name('printerStore');


    Route::get('/printer/{id}/edit', 'edit')->name('printerEdit');
    Route::post('/printer/{id}/update', 'update')->name('printerUpdate');
    Route::delete('/printer/destory', 'printerDestory')->name('printerDestory');
});
Route::prefix('/module')->group(function () {
    Route::controller(moduleController::class)->group(function () {
        Route::get('/list', 'index')->name('module.index');
        Route::post('/upload', 'uploadModule')->name('module.upload');
        Route::get('/install', 'install')->name('module.install');
        Route::get('/uninstall', 'uninstall')->name('module.uninstall');
        Route::delete('/delete', 'delete')->name('module.delete');
    });
});










//============================ End::stock history ============================================



//============================ End::Business Settings ============================================



//============================ Being: Product ===================================================

// ====>    Brand
Route::controller(BrandController::class)->group(function () {
    Route::get('/brand-datas', 'datas')->name('brands.data');
    Route::get('/brands', 'index')->name('brands');
    Route::get('/brands/add', 'add')->name('brand.add');
    Route::post('/brands/create', 'create')->name('brand.create');
    Route::get('/brands/edit/{brand}', 'edit')->name('brand.edit');
    Route::put('/brands/update/{brand}', 'update')->name('brand.update');
    Route::delete('/brands/delete/{brand}', 'delete')->name('brand.delete');
});

// ====>    Category
Route::controller(CategoryController::class)->group(function () {
    Route::get('/category-datas', 'datas')->name('categories.data');
    Route::get('/category/sub-category/{id}', 'subCategory')->name('sub.category');
    Route::get('/category', 'index')->name('categories');
    Route::get('/category/add', 'add')->name('category.add');
    Route::post('/category/create', 'create')->name('category.create');
    Route::get('/category/edit/{category}', 'edit')->name('category.edit');
    Route::put('/category/update/{category}', 'update')->name('category.update');
    Route::delete('/category/delete/{category}', 'delete')->name('category.delete');
});

// ====>    Generic
Route::controller(GenericController::class)->group(function () {
    Route::get('/generic-datas', 'datas')->name('generic.data');
    Route::get('/generic', 'index')->name('generic');
    Route::get('/generic/add', 'add')->name('generic.add');
    Route::post('/generic/create', 'create')->name('generic.create');
    Route::get('/generic/edit/{generic}', 'edit')->name('generic.edit');
    Route::put('/generic/update/{generic}', 'update')->name('generic.update');
    Route::delete('/generic/delete/{generic}', 'delete')->name('generic.delete');
});

// ====>    Manufacturer
Route::controller(ManufacturerController::class)->group(function () {
    Route::get('/manufacturer-datas', 'datas')->name('manufacturer.data');
    Route::get('/manufacturer', 'index')->name('manufacturer');
    Route::get('/manufacturer/add', 'add')->name('manufacturer.add');
    Route::post('/manufacturer/create', 'create')->name('manufacturer.create');
    Route::get('/manufacturer/edit/{manufacturer}', 'edit')->name('manufacturer.edit');
    Route::put('/manufacturer/update/{manufacturer}', 'update')->name('manufacturer.update');
    Route::delete('/manufacturer/delete/{manufacturer}', 'delete')->name('manufacturer.delete');
});

// ====>    Unit Category
Route::controller(UnitCategoryController::class)->group(function () {
    Route::get('/unit-category/datas', 'unitCategoryDatas')->name('unit-category.data');
    Route::get('/unit-category/uom-datas', 'uomDatas')->name('unit-category.uomDatas');

    Route::get('/unit-category', 'index')->name('unit-category');
    Route::get('/unit-category/add', 'add')->name('unit-category.add');
    Route::post('/unit-category/create', 'create')->name('unit-category.create');
    Route::get('/unit-category/edit/{unitCategory}', 'edit')->name('unit-category.edit');
    Route::put('/unit-category/update/{unitCategory}', 'update')->name('unit-category.update');
    Route::delete('/unit-category/delete/{unitCategory}', 'delete')->name('unit-category.delete');
});

// ====>    UoM
Route::controller(UoMController::class)->group(function () {
    Route::get('/uom', 'index')->name('uom');
    Route::get('/uom/add', 'add')->name('uom.add');
    Route::post('/uom/create', 'create')->name('uom.create');
    Route::get('/uom/edit/{uom}', 'edit')->name('uom.edit');
    Route::put('/uom/update/{uom}', 'update')->name('uom.update');
    Route::delete('/uom/delete/{uom}', 'delete')->name('uom.delete');

    Route::get('/uom/check-uom/{id}', 'checkUoM')->name('check-uom');
    Route::get('/uom/get/{id}', 'getUomByUomId');
});

// ====>    VARIATIONS
Route::controller(VariationController::class)->group(function () {
    Route::get('/variation-datas', 'variationDatas')->name('variations.data');
    Route::get('/variation-values/{id}', 'value')->name('variation.values');
    Route::get('/variation', 'index')->name('variations');
    Route::get('/variation/add', 'add')->name('variation.add');
    Route::post('/variation/create', 'create')->name('variation.create');
    Route::get('/variation/edit/{variation}', 'edit')->name('variation.edit');
    Route::put('/variation/update/{variation}', 'update')->name('variation.update');
    Route::delete('/variation/delete/{variation}', 'delete')->name('variation.delete');
});

// ====>    PRODUCTS
Route::controller(ProductController::class)->group(function () {
    Route::get('/product-datas', 'productDatas')->name('product.data');
    Route::get('/product', 'index')->name('products');
    Route::get('/product/add', 'add')->name('product.add');
    Route::get('/product/quick-add', 'quickAdd')->name('product.quickAdd');
    Route::post('/product/create', 'create')->name('product.create');
    Route::get('/product/edit/{product}', 'edit')->name('product.edit');
    Route::put('/product/update/{product}', 'update')->name('product.update');
    Route::delete('/product/delete/{product}', 'delete')->name('product.delete');

    // product variation delete
    Route::delete('/product-variation/delete/{id}', 'deleteProductVariation')->name('product-variation.delete');
});

// ====>    PRICEGROUP
// Route::controller(PriceGroupController::class)->group(function () {
//     Route::get('/price-group-datas', 'datas')->name('price-group.datas');
//     Route::get('/price-group', 'index')->name('price-group');
//     Route::get('/price-group/add', 'add')->name('price-group.add');
//     Route::post('/price-group/create', 'create')->name('price-group.create');
//     Route::get('/price-group/edit/{priceGroup}', 'edit')->name('price-group.edit');
//     Route::put('/price-group/update/{priceGroup}', 'update')->name('price-group.update');
//     Route::delete('/price-group/delete/{priceGroup}', 'delete')->name('price-group.delete');
// });

// ====>    UOM SELLING PRICE
// Route::controller(UOMSellingPriceController::class)->group(function () {
//     Route::get('/uom-selling-price', 'index')->name('uom-selling-price');
//     Route::get('/uom-selling-price/addOrEdit/{id}', 'addOrEdit')->name('uom-selling-price.addOrEdit');
//     Route::post('/uom-selling-price/create/{id}', 'create')->name('uom-selling-price.create');
//     Route::put('/uom-selling-price/update/{id}', 'update')->name('uom-selling-price.update');

//     // UOM
//     Route::get('/uom-selling-price/uom/{id}', 'uomSellingPrice')->name('uom-selling-price.uom');
// });

// ====>    IMPORT PRODUCT
Route::controller(ImportProductController::class)->group(function () {
    Route::get('/import-product', 'index')->name('import-product');
    Route::post('/import-product/create', 'create')->name('import-product.create');
    Route::get('/import-product/demo/excel', 'dowloadDemoExcel')->name('download-importProductExcel');
});

// ====>    PRICE LISTS DETAILS
Route::controller(PriceListDetailController::class)->group(function () {
    Route::get('/price-list-detail-datas', 'priceListDetailDatas')->name('price-list-detail-datas');

    Route::get('/price-list-detail', 'index')->name('price-list-detail');
    Route::get('/price-list-detail/add', 'add')->name('price-list-detail.add');
    Route::post('/price-list-detail/create', 'create')->name('price-list-detail.create');
    Route::get('/price-list-detail/edit/{priceList}', 'edit')->name('price-list-detail.edit');
    Route::put('/price-list-detail/update/{priceList}', 'update')->name('price-list-detail.update');
    Route::delete('/price-list-detail/delete/{priceList}', 'delete')->name('price-list-detail.delete');

    // search applied_value
    Route::get('/price-list-detail/search', 'searchAppliedValue');
    Route::get('/import/price-list', 'importTemplate')->name('priceListTemplate');
});

Route::controller(priceListImportController::class)->group(function () {
    Route::post('/price-list/import/{action?}/{id?}', 'import')->name('priceListImport');
    Route::get('/download/price-list/excel', 'dowloadDemoExcel')->name('downloadPrceListExcel');
    // Route::post('/price-list/import/update', 'importUpdate')->name('priceListImportUpdate');
});

Route::controller(TestController::class)->group(function () {
    //    Route::get('/home', 'index')->name('home');

    // Print Labels
    Route::get('/printLabel', 'printLabel');

    // Import Opening Stock
    Route::get('/import-opening-stock', 'importStock');

    // Warranties
    Route::get('/warranites', 'warranties');
    Route::get('/warranty/add', 'warrantyAdd');
    Route::get('/warranty/edit', 'warrantyEdit');
});

//============================ End: Product ==============================================

// Route::get('/test', function () {

//     $data=PriceListDetails::where('id','4')->first();
//     // return view('App.product.product.testProduct');
//     dd([$data->toArray(),getBase($data)->toArray()[0]]);
// });


//Route::get('/users', fn()=>view('App.userManagement.users.index'))->name('user.list');
//Route::get('/viewuser', fn()=>view('App.userManagement.users.view'));
//Route::get('/adduser', fn()=>view('App.userManagement.users.add'))->name('user.add');
//Route::get('/edituser', fn()=>view('App.userManagement.users.edit'))->name('user.edit');
//Route::get('/addnote', fn()=>view('App.userManagement.users.add_note'));
//Route::get('/viewnote', fn()=>view('App.userManagement.users.view_note'));
//
//Route::get('/roles', fn()=>view('App.userManagement.roles.index'));
//Route::get('/addrole', fn()=>view('App.userManagement.roles.add'));
//Route::get('/editrole', fn()=>view('App.userManagement.roles.edit'));

//Contact
Route::prefix('/contacts')->group(function () {
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('import-contacts', ImportContactsController::class);
});

Route::get('/download/contact-excel-template', [ImportContactsController::class, 'dowloadContactExcel'])->name('download-contact-excel');

Route::get('customers/quickadd', [CustomerController::class, 'quickCreateCustomer']);
Route::post('customers', [CustomerController::class, 'quickStoreCustomer']);

Route::resource('customer-group', CustomerGroupController::class);

// Post to reservation folio
Route::get('/reservation/post/{id}/Folio', [saleController::class, 'postToReservationFolio'])->name('postToReservationFolio');
Route::post('/registration/post/Folio', [saleController::class, 'addToReservationFolio'])->name('addToReservationFolio');

//============================ Begin: POS ==============================================

Route::controller(POSController::class)->group(function () {
    // contact
    Route::get('/pos/contacts', 'contactGet')->name('pos.contact.get');
    Route::get('/pos/contact/add', 'contactAdd')->name('pos.contact.add');
    Route::get('/pos/contact/edit/{id}', 'contactEdit')->name('pos.contact.edit');
    Route::put('/pos/contact-phone/update/{id}', 'phoneOnlyUpdate')->name('pos.contact-phone.update');

    Route::get('/pos/create', 'create')->name('pos.create');
    Route::get('/pos/payment-print-layout', 'paymentPrintLayout')->name('pos.pryment-print-layout');

    Route::get('/pos/{posRegisterId}/edit/', 'edit')->name('pos.edit');

    Route::get('/pos/{id}/recent/sale/', 'recentSale')->name('pos.recentSale');
    Route::get('/pos/{posRegisterId}/close/', 'closeSession')->name('pos.closeSession');
    // product
    Route::get('/pos/product-variations', 'productVariationsGet')->name('pos.product-variations');

    // check price list
    Route::get('/pos/pricelist-contact/{id}', 'checkByContact');
    Route::get('/pos/pricelist-location/{id}', 'checkByLocation');

    // get sale product
    Route::get('/pos/sold/{posId}', 'getSoldProduct');
});

Route::prefix('pos')->group(function () {
    Route::controller(posSessionController::class)->group(function () {
        Route::get('select/', 'selectPos')->name('pos.selectPos');
        Route::get('{id}/session/check', 'sessionCheck')->name('pos.sessionCheck');
        Route::get('{id}/session/create', 'sessionCreate')->name('pos.sessionCreate');
        Route::post('{id}/session/store', 'sessionStore')->name('pos.sessionStore');
        Route::post('{id}/session/destory', 'sessionDestory')->name('pos.sessionDestory');
    });
});
//============================ End: POS ==============================================
// POS
// Route::get('/pos/create', function () {
//     return view('App.pos.create');
// });

Route::get('/pos/edit', function () {
    return view('App.pos.edit');
});
