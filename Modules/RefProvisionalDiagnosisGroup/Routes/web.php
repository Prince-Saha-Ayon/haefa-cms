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
        Route::get('refprovisionaldiagnosisgroup','RefProvisionalDiagnosisGroupController@index')->name('menu');
        Route::group(['prefix' => 'refprovisionaldiagnosisgroup', 'as'=>'refprovisionaldiagnosisgroup.'], function () {
        Route::post('datatable-data', 'RefProvisionalDiagnosisGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefProvisionalDiagnosisGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefProvisionalDiagnosisGroupController@edit')->name('edit');
        Route::post('show', 'RefProvisionalDiagnosisGroupController@show')->name('show');
        Route::post('delete', 'RefProvisionalDiagnosisGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'RefProvisionalDiagnosisGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefProvisionalDiagnosisGroupController@change_status')->name('change.status');
    });
});