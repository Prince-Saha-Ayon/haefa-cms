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

//User Routes
Route::group(['middleware' => ['auth']], function () {
Route::get('refaddress-type','RefAddressTypeController@index')->name('menu');
Route::group(['prefix' => 'refaddress-type', 'as'=>'refaddresstype.'], function () {
    Route::post('datatable-data', 'RefAddressTypeController@get_datatable_data')->name('datatable.data');
    Route::post('store-or-update', 'RefAddressTypeController@store_or_update_data')->name('store.or.update');
    Route::post('edit', 'RefAddressTypeController@edit')->name('edit');
    Route::post('show', 'RefAddressTypeController@show')->name('show');
    Route::post('delete', 'RefAddressTypeController@delete')->name('delete');
    Route::post('bulk-delete', 'RefAddressTypeController@bulk_delete')->name('bulk.delete');
    Route::post('change-status', 'RefAddressTypeController@change_status')->name('change.status');
});
});
