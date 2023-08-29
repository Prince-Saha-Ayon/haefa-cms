<?php

use Illuminate\Support\Facades\Route;

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
    Route::get('patient', 'PatientController@index')->name('patient');
    Route::group(['prefix' => 'patient', 'as'=>'patient.'], function () {
        Route::post('datatable-data', 'PatientController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'PatientController@store_or_update_data')->name('store.or.update');
        Route::post('store-or-update1', 'PatientController@store_or_update_data1')->name('store.or.update1');
        Route::post('delete', 'PatientController@delete')->name('delete');
        Route::post('bulk-delete', 'PatientController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'PatientController@change_status')->name('change.status');
        Route::post('show', 'PatientController@show')->name('show');
        Route::post('showid', 'PatientController@showid')->name('showid');
        Route::get('edit/{id}', 'PatientController@edit')->name('edit');
    });
});
