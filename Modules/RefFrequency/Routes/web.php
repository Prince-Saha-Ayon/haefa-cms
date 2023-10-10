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
        Route::get('reffrequency','RefFrequencyController@index')->name('menu');
        Route::group(['prefix' => 'reffrequency', 'as'=>'reffrequency.'], function () {
        Route::post('datatable-data', 'RefFrequencyController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefFrequencyController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefFrequencyController@edit')->name('edit');
        Route::post('show', 'RefFrequencyController@show')->name('show');
        Route::post('delete', 'RefFrequencyController@delete')->name('delete');
        Route::post('bulk-delete', 'RefFrequencyController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefFrequencyController@change_status')->name('change.status');
    });
});
