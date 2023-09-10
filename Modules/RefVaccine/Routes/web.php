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

//RefVaccine Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refvaccine','RefVaccineController@index')->name('menu');
    Route::group(['prefix' => 'refvaccine', 'as'=>'refvaccine.'], function () {
        Route::post('datatable-data', 'RefVaccineController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefVaccineController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefVaccineController@edit')->name('edit');
        Route::post('show', 'RefVaccineController@show')->name('show');
        Route::get('workplaces', 'RefVaccineController@workplaces_departments')->name('workplaces_departments');
        Route::post('delete', 'RefVaccineController@delete')->name('delete');
        Route::post('bulk-delete', 'RefVaccineController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefVaccineController@change_status')->name('change.status');
    });
});

