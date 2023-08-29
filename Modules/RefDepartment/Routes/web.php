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

//Refdepartment Routes
Route::group(['middleware' => ['auth']], function () {
Route::get('refdepartment','RefDepartmentController@index')->name('menu');
Route::group(['prefix' => 'refchiefcomplain', 'as'=>'refdepartment.'], function () {
    Route::post('datatable-data', 'RefDepartmentController@get_datatable_data')->name('datatable.data');
    Route::post('store-or-update', 'RefDepartmentController@store_or_update_data')->name('store.or.update');
    Route::post('edit', 'RefDepartmentController@edit')->name('edit');
    Route::post('show', 'RefDepartmentController@show')->name('show');
    Route::get('workplaces', 'RefDepartmentController@workplaces')->name('workplaces');
    Route::post('delete', 'RefDepartmentController@delete')->name('delete');
    Route::post('bulk-delete', 'RefDepartmentController@bulk_delete')->name('bulk.delete');
    Route::post('change-status', 'RefDepartmentController@change_status')->name('change.status');
});
});