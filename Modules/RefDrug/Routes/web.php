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
        Route::get('refdrug','RefDrugController@index')->name('menu');
        Route::group(['prefix' => 'refdrug', 'as'=>'refdrug.'], function () {
        Route::post('datatable-data', 'RefDrugController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDrugController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDrugController@edit')->name('edit');
        Route::post('show', 'RefDrugController@show')->name('show');
        Route::post('delete', 'RefDrugController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDrugController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDrugController@change_status')->name('change.status');
    });
});
