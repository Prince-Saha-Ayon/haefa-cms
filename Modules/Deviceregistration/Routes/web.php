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
    Route::get('deviceregistration', 'DeviceregistrationController@index')->name('deviceregistration');
    Route::group(['prefix' => 'deviceregistration', 'as'=>'deviceregistration.'], function () {
        Route::post('datatable-data', 'DeviceregistrationController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'DeviceregistrationController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'DeviceregistrationController@edit')->name('edit');
        Route::post('delete', 'DeviceregistrationController@delete')->name('delete');
        Route::post('bulk-delete', 'DeviceregistrationController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'DeviceregistrationController@change_status')->name('change.status');
    });
});
