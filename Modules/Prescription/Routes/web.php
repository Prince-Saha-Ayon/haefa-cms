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
    Route::get('prescription', 'PrescriptionController@index')->name('prescription');
    Route::group(['prefix' => 'prescription', 'as'=>'prescription.'], function () {
        Route::post('datatable-data', 'PrescriptionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PrescriptionController@store_or_update_data')->name('store.or.update');
        Route::post('store-or-update1', 'PrescriptionController@store_or_update_data1')->name('store.or.update1');
        Route::post('delete', 'PrescriptionController@delete')->name('delete');
        Route::post('bulk-delete', 'PrescriptionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PrescriptionController@change_status')->name('change.status');
        Route::post('show', 'PrescriptionController@show')->name('show');
    });
});

