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
    Route::get('refbiopsyresult','RefBiopsyResultController@index')->name('menu');
    Route::group(['prefix' => 'refbiopsyresult', 'as'=>'refbiopsyresult.'], function () {
        Route::post('datatable-data', 'RefBiopsyResultController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefBiopsyResultController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefBiopsyResultController@edit')->name('edit');
        Route::post('show', 'RefBiopsyResultController@show')->name('show');
        Route::post('delete', 'RefBiopsyResultController@delete')->name('delete');
        Route::post('bulk-delete', 'RefBiopsyResultController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefBiopsyResultController@change_status')->name('change.status');
    });
});
