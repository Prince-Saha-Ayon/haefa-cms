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
        Route::get('reflabinvestigation','RefLabInvestigationController@index')->name('menu');
        Route::group(['prefix' => 'reflabinvestigation', 'as'=>'reflabinvestigation.'], function () {
        Route::post('datatable-data', 'RefLabInvestigationController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefLabInvestigationController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefLabInvestigationController@edit')->name('edit');
        Route::post('show', 'RefLabInvestigationController@show')->name('show');
        Route::post('delete', 'RefLabInvestigationController@delete')->name('delete');
        Route::post('bulk-delete', 'RefLabInvestigationController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefLabInvestigationController@change_status')->name('change.status');
    });
});
