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
    Route::get('refdrugform','RefDrugFormController@index')->name('menu');
    Route::group(['prefix' => 'refdrugform', 'as'=>'refdrugform.'], function () {
        Route::post('datatable-data', 'RefDrugFormController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDrugFormController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDrugFormController@edit')->name('edit');
        Route::post('show', 'RefDrugFormController@show')->name('show');
        Route::post('delete', 'RefDrugFormController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDrugFormController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDrugFormController@change_status')->name('change.status');
    });
});

