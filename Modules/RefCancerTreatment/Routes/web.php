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
        Route::get('refcancertreatment','RefCancerTreatmentController@index')->name('menu');
        Route::group(['prefix' => 'refcancertreatment', 'as'=>'refcancertreatment.'], function () {
        Route::post('datatable-data', 'RefCancerTreatmentController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefCancerTreatmentController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefCancerTreatmentController@edit')->name('edit');
        Route::post('show', 'RefCancerTreatmentController@show')->name('show');
        Route::post('delete', 'RefCancerTreatmentController@delete')->name('delete');
        Route::post('bulk-delete', 'RefCancerTreatmentController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefCancerTreatmentController@change_status')->name('change.status');
    });
});