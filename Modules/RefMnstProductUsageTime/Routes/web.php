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

//RefMnstProductUsageTime Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refmnstproductusagetime','RefMnstProductUsageTimeController@index')->name('menu');
    Route::group(['prefix' => 'refmnstproductusagetime', 'as'=>'refmnstproductusagetime.'], function () {
        Route::post('datatable-data', 'RefMnstProductUsageTimeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefMnstProductUsageTimeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefMnstProductUsageTimeController@edit')->name('edit');
        Route::post('show', 'RefMnstProductUsageTimeController@show')->name('show');
        Route::post('delete', 'RefMnstProductUsageTimeController@delete')->name('delete');
        Route::post('bulk-delete', 'RefMnstProductUsageTimeController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefMnstProductUsageTimeController@change_status')->name('change.status');
    });
});
