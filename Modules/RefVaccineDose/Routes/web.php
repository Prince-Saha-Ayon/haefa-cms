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
        Route::get('refvaccinedose','RefVaccineDoseController@index')->name('menu');
        Route::group(['prefix' => 'refvaccinedose', 'as'=>'refvaccinedose.'], function () {
        Route::post('datatable-data', 'RefVaccineDoseController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefVaccineDoseController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefVaccineDoseController@edit')->name('edit');
        Route::post('show', 'RefVaccineDoseController@show')->name('show');
        Route::post('delete', 'RefVaccineDoseController@delete')->name('delete');
        Route::post('bulk-delete', 'RefVaccineDoseController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefVaccineDoseController@change_status')->name('change.status');
    });
});
