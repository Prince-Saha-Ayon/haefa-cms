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

//RefReferral Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refreferral','RefReferralController@index')->name('menu');
    Route::group(['prefix' => 'refreferral', 'as'=>'refreferral.'], function () {
        Route::post('datatable-data', 'RefReferralController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefReferralController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefReferralController@edit')->name('edit');
        Route::post('show', 'RefReferralController@show')->name('show');
        Route::post('delete', 'RefReferralController@delete')->name('delete');
        Route::post('bulk-delete', 'RefReferralController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefReferralController@change_status')->name('change.status');
    });
});