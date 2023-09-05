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

Route::group(['middleware' => ['auth']], function () {
    Route::get('refvaccineadult','RefVaccineAdultController@index')->name('menu');
    Route::group(['prefix' => 'refvaccineadult', 'as'=>'refvaccineadult.'], function () {
        Route::post('datatable-data', 'RefVaccineAdultController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefVaccineAdultController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefVaccineAdultController@edit')->name('edit');
        Route::post('show', 'RefVaccineAdultController@show')->name('show');
        Route::get('workplaces', 'RefVaccineAdultController@workplaces_departments')->name('workplaces_departments');
        Route::post('delete', 'RefVaccineAdultController@delete')->name('delete');
        Route::post('bulk-delete', 'RefVaccineAdultController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefVaccineAdultController@change_status')->name('change.status');
    });
});
