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
        Route::get('refprovisionaldiagnosis','RefProvisionalDiagnosisController@index')->name('menu');
        Route::group(['prefix' => 'refprovisionaldiagnosis', 'as'=>'refprovisionaldiagnosis.'], function () {
        Route::post('datatable-data', 'RefProvisionalDiagnosisController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefProvisionalDiagnosisController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefProvisionalDiagnosisController@edit')->name('edit');
        Route::post('show', 'RefProvisionalDiagnosisController@show')->name('show');
        Route::post('delete', 'RefProvisionalDiagnosisController@delete')->name('delete');
        Route::post('bulk-delete', 'RefProvisionalDiagnosisController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefProvisionalDiagnosisController@change_status')->name('change.status');
    });
});
