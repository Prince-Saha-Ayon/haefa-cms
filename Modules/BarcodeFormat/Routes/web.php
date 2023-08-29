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
    Route::get('bformat', 'BarcodeFormatController@index')->name('bformat');
    Route::group(['prefix' => 'bformat', 'as'=>'bformat.'], function () {
        Route::post('datatable-data', 'BarcodeFormatController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BarcodeFormatController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BarcodeFormatController@edit')->name('edit');
        Route::post('delete', 'BarcodeFormatController@delete')->name('delete');
        Route::post('bulk-delete', 'BarcodeFormatController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'BarcodeFormatController@change_status')->name('change.status');
    });
});
