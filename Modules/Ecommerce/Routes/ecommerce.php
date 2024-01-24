<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Ecommerce\Http\Controllers\CustomerAuth\LoginController;
use Modules\Ecommerce\Http\Controllers\CustomerAuth\RegisterController;
use Modules\Ecommerce\Http\Controllers\CustomerAuthController;
use Modules\Ecommerce\Http\Controllers\EcommerceController;


Route::get('/', 'EcommerceController@index')->name('ecommerce.home');
Route::get('/product-details/{id}',[EcommerceController::class, 'productDetail'])->name('ecommerce.product-details');

Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('ecommerce.login');
    Route::get('/request-code/{type}', [LoginController::class, 'requestCode'])
        ->name('ecommerce.request.code');
    Route::post('/login', [LoginController::class, 'login'])
        ->name('ecommerce.verification.confirm');

    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('ecommerce.register');
    Route::post('/register', [RegisterController::class, 'create'])->name('ecommerce.register.store');


//    Route::get('/verification', [LoginController::class, 'verification'])->name('ecommerce.verification');
});

Route::middleware('auth:customer')->group(function () {
    Route::post('/ecommerce/logout', [LoginController::class, 'logout'])
        ->name('ecommerce.logout');
    Route::post('/add-to-cart', [EcommerceController::class, 'pushCart'])
        ->name('ecommerce.add-to-cart');
    Route::get('/get/shopping-cart/data', [EcommerceController::class, 'getShoppingCart'])
        ->name('ecommerce.get.shopping-cart.data');
    Route::get('/get/shopping-cart/total-item', [EcommerceController::class, 'getTotalItemInCart'])
        ->name('ecommerce.get.shopping-cart.total');
    Route::post('/remove/shopping-cart-item/{cart_id}', [EcommerceController::class, 'removeCartItem'])
        ->name('ecommerce.remove.shopping-cart-item');
});

