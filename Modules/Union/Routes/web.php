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

//Union Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('union','UnionController@index')->name('menu');
    Route::group(['prefix' => 'union', 'as'=>'union.'], function () {
        Route::post('datatable-data', 'UnionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UnionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UnionController@edit')->name('edit');
        Route::post('show', 'UnionController@show')->name('show');
        Route::post('delete', 'UnionController@delete')->name('delete');
        Route::post('bulk-delete', 'UnionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UnionController@change_status')->name('change.status');
    });
});