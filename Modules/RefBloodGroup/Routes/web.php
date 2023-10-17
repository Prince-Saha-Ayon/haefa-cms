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

Route::prefix('refbloodgroup')->group(function() {
    Route::get('/', 'RefBloodGroupController@index');
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('refbloodgroup','RefBloodGroupController@index')->name('menu');
    Route::group(['prefix' => 'refbloodgroup', 'as'=>'refbloodgroup.'], function () {
        Route::post('datatable-data', 'RefBloodGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefBloodGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefBloodGroupController@edit')->name('edit');
        Route::post('show', 'RefBloodGroupController@show')->name('show');
        Route::post('delete', 'RefBloodGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'RefBloodGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefBloodGroupController@change_status')->name('change.status');
    });
});