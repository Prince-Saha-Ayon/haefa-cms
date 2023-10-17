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
        Route::get('refdruggroup','RefDrugGroupController@index')->name('menu');
        Route::group(['prefix' => 'refdruggroup', 'as'=>'refdruggroup.'], function () {
        Route::post('datatable-data', 'RefDrugGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDrugGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDrugGroupController@edit')->name('edit');
        Route::post('show', 'RefDrugGroupController@show')->name('show');
        Route::post('delete', 'RefDrugGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDrugGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDrugGroupController@change_status')->name('change.status');
    });
});
