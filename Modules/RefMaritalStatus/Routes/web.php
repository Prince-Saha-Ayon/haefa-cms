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
        Route::get('refmaritalstatus','RefMaritalStatusController@index')->name('menu');
        Route::group(['prefix' => 'refmaritalstatus', 'as'=>'refmaritalstatus.'], function () {
        Route::post('datatable-data', 'RefMaritalStatusController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefMaritalStatusController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefMaritalStatusController@edit')->name('edit');
        Route::post('show', 'RefMaritalStatusController@show')->name('show');
        Route::post('delete', 'RefMaritalStatusController@delete')->name('delete');
        Route::post('bulk-delete', 'RefMaritalStatusController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefMaritalStatusController@change_status')->name('change.status');
    });
});