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

//Refillness Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refillness','RefIllnessController@index')->name('menu');
    Route::group(['prefix' => 'refillness', 'as'=>'refillness.'], function () {
        Route::post('datatable-data', 'RefIllnessController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefIllnessController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefIllnessController@edit')->name('edit');
        Route::post('show', 'RefIllnessController@show')->name('show');
        Route::post('delete', 'RefIllnessController@delete')->name('delete');
        Route::post('bulk-delete', 'RefIllnessController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefIllnessController@change_status')->name('change.status');
    });
});
