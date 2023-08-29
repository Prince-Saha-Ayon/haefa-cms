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
    Route::get('refeducation','RefEducationController@index')->name('menu');
    Route::group(['prefix' => 'refeducation', 'as'=>'refeducation.'], function () {
        Route::post('datatable-data', 'RefEducationController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefEducationController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefEducationController@edit')->name('edit');
        Route::post('show', 'RefEducationController@show')->name('show');
        Route::post('delete', 'RefEducationController@delete')->name('delete');
        Route::post('bulk-delete', 'RefEducationController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefEducationController@change_status')->name('change.status');
    });
});