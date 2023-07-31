<?php

use Illuminate\Support\Facades\Route;

use Modules\RoomManagement\Http\Controllers\RoomTypeController;
use Modules\RoomManagement\Http\Controllers\RoomController;
use Modules\RoomManagement\Http\Controllers\BedController;
use Modules\RoomManagement\Http\Controllers\BedTypeController;
use Modules\RoomManagement\Http\Controllers\FacilityController;
use Modules\RoomManagement\Http\Controllers\RoomSaleController;
use Modules\RoomManagement\Http\Controllers\RoomRateController;


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

// Room Management
Route::prefix('/room-management')->group(function () {
    Route::resource('room-type', RoomTypeController::class);
    Route::resource('bed-type', BedTypeController::class);
    Route::resource('room', RoomController::class);
    Route::resource('bed', BedController::class);
    Route::resource('facility', FacilityController::class);
    Route::resource('room-rate', RoomRateController::class);
    Route::resource('room-sale', RoomSaleController::class);
});

