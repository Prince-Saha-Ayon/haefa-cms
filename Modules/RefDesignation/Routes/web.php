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

//Refdesignation Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refdesignation','RefDesignationController@index')->name('menu');
    Route::group(['prefix' => 'refdesignation', 'as'=>'refdesignation.'], function () {
        Route::post('datatable-data', 'RefDesignationController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDesignationController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDesignationController@edit')->name('edit');
        Route::post('show', 'RefDesignationController@show')->name('show');
        Route::get('workplaces', 'RefDesignationController@workplaces_departments')->name('workplaces_departments');
        Route::post('delete', 'RefDesignationController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDesignationController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDesignationController@change_status')->name('change.status');
    });
});
