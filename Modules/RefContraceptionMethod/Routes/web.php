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
        Route::get('refcontraceptionmethod','RefContraceptionMethodController@index')->name('menu');
        Route::group(['prefix' => 'refcontraceptionmethod', 'as'=>'refcontraceptionmethod.'], function () {
        Route::post('datatable-data', 'RefContraceptionMethodController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefContraceptionMethodController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefContraceptionMethodController@edit')->name('edit');
        Route::post('show', 'RefContraceptionMethodController@show')->name('show');
        Route::post('delete', 'RefContraceptionMethodController@delete')->name('delete');
        Route::post('bulk-delete', 'RefContraceptionMethodController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefContraceptionMethodController@change_status')->name('change.status');
    });
});
