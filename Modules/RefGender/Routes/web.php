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

//Refgender Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refgender','RefGenderController@index')->name('menu');
    Route::group(['prefix' => 'refgender', 'as'=>'refgender.'], function () {
        Route::post('datatable-data', 'RefGenderController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefGenderController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefGenderController@edit')->name('edit');
        Route::post('show', 'RefGenderController@show')->name('show');
        Route::post('delete', 'RefGenderController@delete')->name('delete');
        Route::post('bulk-delete', 'RefGenderController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefGenderController@change_status')->name('change.status');
    });
});