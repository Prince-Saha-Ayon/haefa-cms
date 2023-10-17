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
        Route::get('refreligion','RefReligionController@index')->name('menu');
        Route::group(['prefix' => 'refreligion', 'as'=>'refreligion.'], function () {
        Route::post('datatable-data', 'RefReligionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefReligionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefReligionController@edit')->name('edit');
        Route::post('show', 'RefReligionController@show')->name('show');
        Route::post('delete', 'RefReligionController@delete')->name('delete');
        Route::post('bulk-delete', 'RefReligionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefReligionController@change_status')->name('change.status');
    });
});
