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

//RefMenstruationProduct Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refmenstruationproduct','RefMenstruationProductController@index')->name('menu');
    Route::group(['prefix' => 'refmenstruationproduct', 'as'=>'refmenstruationproduct.'], function () {
        Route::post('datatable-data', 'RefMenstruationProductController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefMenstruationProductController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefMenstruationProductController@edit')->name('edit');
        Route::post('show', 'RefMenstruationProductController@show')->name('show');
        Route::post('delete', 'RefMenstruationProductController@delete')->name('delete');
        Route::post('bulk-delete', 'RefMenstruationProductController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefMenstruationProductController@change_status')->name('change.status');
    });
});
