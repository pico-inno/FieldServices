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
use Modules\ComboKit\Http\Controllers\ComboKitController;

Route::resource('combokit', ComboKitController::class);


Route::get('combokit/{combokit}/make_default', [ComboKitController::class, 'makeDefault'])->name('combokit.make-default');
