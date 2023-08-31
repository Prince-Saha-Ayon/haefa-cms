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
    Route::get('refdiseasegroups', 'RefDiseaseGroupsController@index')->name('refdiseasegroups');
    Route::group(['prefix' => 'refdiseasegroups', 'as'=>'refdiseasegroups.'], function () {
        Route::post('datatable-data', 'RefDiseaseGroupsController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefDiseaseGroupsController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefDiseaseGroupsController@edit')->name('edit');
        Route::post('delete', 'RefDiseaseGroupsController@delete')->name('delete');
        Route::post('bulk-delete', 'RefDiseaseGroupsController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefDiseaseGroupsController@change_status')->name('change.status');
    });
});
