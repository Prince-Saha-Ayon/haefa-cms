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

//HealthCenter Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('healthcenter', 'HealthCenterController@index')->name('menu');
    Route::group(['prefix' => 'healthcenter', 'as' => 'healthcenter.'], function () {
        Route::post('datatable-data', 'HealthCenterController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'HealthCenterController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'HealthCenterController@edit')->name('edit');
        Route::post('show', 'HealthCenterController@show')->name('show');
        Route::post('delete', 'HealthCenterController@delete')->name('delete');
        Route::post('bulk-delete', 'HealthCenterController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'HealthCenterController@change_status')->name('change.status');
    });
});


