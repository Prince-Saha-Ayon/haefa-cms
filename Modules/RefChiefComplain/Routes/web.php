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
Route::get('chiefcomplain','RefChiefComplainController@index')->name('menu');
Route::group(['prefix' => 'chiefcomplain', 'as'=>'chiefcomplain.'], function () {
    Route::post('datatable-data', 'RefChiefComplainController@get_datatable_data')->name('datatable.data');
    Route::post('store-or-update', 'RefChiefComplainController@store_or_update_data')->name('store.or.update');
    Route::post('edit', 'RefChiefComplainController@edit')->name('edit');
    Route::post('show', 'RefChiefComplainController@show')->name('show');
    Route::post('delete', 'RefChiefComplainController@delete')->name('delete');
    Route::post('bulk-delete', 'RefChiefComplainController@bulk_delete')->name('bulk.delete');
    Route::post('change-status', 'RefChiefComplainController@change_status')->name('change.status');
});
});