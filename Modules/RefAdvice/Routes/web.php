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
        Route::get('refadvice','RefAdviceController@index')->name('menu');
        Route::group(['prefix' => 'refadvice', 'as'=>'refadvice.'], function () {
        Route::post('datatable-data', 'RefAdviceController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefAdviceController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefAdviceController@edit')->name('edit');
        Route::post('show', 'RefAdviceController@show')->name('show');
        Route::post('delete', 'RefAdviceController@delete')->name('delete');
        Route::post('bulk-delete', 'RefAdviceController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefAdviceController@change_status')->name('change.status');
    });
});

