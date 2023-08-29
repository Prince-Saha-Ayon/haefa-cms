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
    Route::get('bgenerate', 'BarcodeGeneratController@index')->name('bgenerate');
    Route::get('latest-range-generate/{id}', 'BarcodeGeneratController@latest_range_generate')->name('latest.range.generate');
    Route::group(['prefix' => 'bgenerate', 'as'=>'bgenerate.'], function () {
        Route::post('datatable-data', 'BarcodeGeneratController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BarcodeGeneratController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BarcodeGeneratController@edit')->name('edit');
        Route::post('delete', 'BarcodeGeneratController@delete')->name('delete');
        Route::post('bulk-delete', 'BarcodeGeneratController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'BarcodeGeneratController@change_status')->name('change.status');
    });
});
