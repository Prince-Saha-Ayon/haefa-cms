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

//Upazila Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('upazila','UpazilaController@index')->name('menu');
    Route::group(['prefix' => 'upazila', 'as'=>'upazila.'], function () {
        Route::post('datatable-data', 'UpazilaController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UpazilaController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UpazilaController@edit')->name('edit');
        Route::post('show', 'UpazilaController@show')->name('show');
        Route::post('delete', 'UpazilaController@delete')->name('delete');
        Route::post('bulk-delete', 'UpazilaController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UpazilaController@change_status')->name('change.status');
    });
});

