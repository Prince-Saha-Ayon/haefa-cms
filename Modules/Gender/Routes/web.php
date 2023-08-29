<?php

use Illuminate\Support\Facades\Route;

/*  
|--------------------------------------------------------------------------
| Web Routes deviceregistration
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth']], function () {
    Route::get('gender', 'GenderController@index')->name('gender');
    Route::group(['prefix' => 'gender', 'as'=>'gender.'], function () {
        Route::post('datatable-data', 'GenderController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'GenderController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'GenderController@edit')->name('edit');
        Route::post('delete', 'GenderController@delete')->name('delete');
        Route::post('bulk-delete', 'GenderController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'GenderController@change_status')->name('change.status');
    });
});
