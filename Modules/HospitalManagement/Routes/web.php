<?php

use Illuminate\Support\Facades\Route;
use Modules\HospitalManagement\Http\Controllers\hospitalFolioInvoicesController;
use Modules\HospitalManagement\Http\Controllers\hospitalRegistrationsController;

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

Route::prefix('hospital')->group(function() {

    //============================ start::Registration ============================================
    Route::controller(hospitalRegistrationsController::class)->group(function(){
        Route::get('/registration/list','index')->name('registration_list');
        Route::get('/registration/create','create')->name('registration_create');
        Route::post('/registration/create','store')->name('registration_store');


        Route::get('/registration/data','data');
        Route::get('/view/registration/{id}/data','view')->name('registration_view');

        Route::get('/view/timeLine/', 'timeLine')->name('registration_timeLine');


        Route::get('/registration/{id}/edit','edit')->name('registration_edit');
        Route::get('/join/{id}/registration', 'joinRegistraion')->name('joinRegistraionModal');
        Route::put('/join/{id}/registration', 'updateJoinRegistraion')->name('joinRegistraion');

        Route::get('/registration/onlyInfo/{id}/edit', 'registrationInfoEdit')->name('registration_info_edit');
        Route::put('/registration/onlyInfo/{id}/update', 'registrationInfoUpdate')->name('registration_info_update');


        Route::get('/registration/room/{id}/edit', 'registrationRoomEdit')->name('registration_room_edit');
        Route::put('/registration/room/{id}/update', 'quickRoomDataUpdate')->name('registrationRoomInfoUpdate');

        Route::post('/registration/{id}/update','update')->name('registration_update');


        Route::delete('/registration/{id}/delete','destory')->name('registration_delete');

    });




    //============================ End::Registration ============================================
});
    Route::controller(hospitalFolioInvoicesController::class)->group(function(){
        Route::get('/get/registration/folios', 'getJoinedFolioDatas');
        Route::get('/get/folios/AllTabs/', 'getFolioInvoicesForAllTab');
    });
