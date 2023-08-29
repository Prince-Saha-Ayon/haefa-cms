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

//RefSocialBehavior Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refsocialbehavior','RefSocialBehaviorController@index')->name('menu');
    Route::group(['prefix' => 'refsocialbehavior', 'as'=>'refsocialbehavior.'], function () {
        Route::post('datatable-data', 'RefSocialBehaviorController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefSocialBehaviorController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefSocialBehaviorController@edit')->name('edit');
        Route::post('show', 'RefSocialBehaviorController@show')->name('show');
        Route::post('delete', 'RefSocialBehaviorController@delete')->name('delete');
        Route::post('bulk-delete', 'RefSocialBehaviorController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefSocialBehaviorController@change_status')->name('change.status');
    });
});
