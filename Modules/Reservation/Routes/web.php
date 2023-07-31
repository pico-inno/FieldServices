<?php

use Illuminate\Support\Facades\Route;

use Modules\Reservation\Http\Controllers\ReservationController;
use Modules\Reservation\Http\Controllers\RoomDashboardController;

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

// Reservation
Route::resource('reservation', ReservationController::class);

// Get reservation data to show in reservation overview dynamically
Route::get('/reservations/{id}', [ReservationController::class, 'getReservation']);
Route::get('/reservations/multiple/{ids}', [ReservationController::class, 'getMultipleReservations']);

// Edit the reservation info and reserved room info in reservation overview
Route::get('/reservation/info/{id}/edit', [ReservationController::class, 'editReservationInfo'])->name('editReservationInfo');
Route::put('/reservation/info/{id}', [ReservationController::class, 'updateReservationInfo'])->name('updateReservationInfo');

Route::get('/reservation/roominfo/{id}/edit', [ReservationController::class, 'editReservedRoom'])->name('editReservedRoom');
Route::put('/reservation/roominfo/{id}', [ReservationController::class, 'updateReservedRoom'])->name('updateReservedRoom');

// Edit reserved guest info in reservation overview
Route::put('/reservation/guestinfo/{id}', [ReservationController::class, 'updateGuestInfo'])->name('updateGuestInfo');

// Room Dashboard
Route::get('/room-dashboard', [RoomDashboardController::class, 'showTimeline']);
