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
        Route::get('refduration','RefDurationController@index')->name('menu');
        Route::group(['prefix' => 'refduration', 'as'=>'refduration.'], function () {
        Route::post('datatable-data', 'RefDurationController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDurationController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDurationController@edit')->name('edit');
        Route::post('show', 'RefDurationController@show')->name('show');
        Route::post('delete', 'RefDurationController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDurationController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDurationController@change_status')->name('change.status');
    });
});