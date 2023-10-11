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
        Route::get('refillfamilymember','RefIllFamilyMemberController@index')->name('menu');
        Route::group(['prefix' => 'refillfamilymember', 'as'=>'refillfamilymember.'], function () {
        Route::post('datatable-data', 'RefIllFamilyMemberController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefIllFamilyMemberController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefIllFamilyMemberController@edit')->name('edit');
        Route::post('show', 'RefIllFamilyMemberController@show')->name('show');
        Route::post('delete', 'RefIllFamilyMemberController@delete')->name('delete');
        Route::post('bulk-delete', 'RefIllFamilyMemberController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefIllFamilyMemberController@change_status')->name('change.status');
    });
});
