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
    Route::get('barcodeprint', 'BarcodePrintController@index')->name('barcodeprint');
    Route::get('latest-range/{id}', 'BarcodePrintController@latest_range')->name('latest.range');
    Route::get('get-barcodes/{barcode_type}', 'BarcodePrintController@get_barcodes')->name('get.barcodes');
    Route::get('view-barcodes/{startValue}', 'BarcodePrintController@view_barcodes')->name('view.barcodes');
    Route::group(['prefix' => 'barcodeprint', 'as'=>'barcodeprint.'], function () {
        Route::post('barcode/product-autocomplete-search', 'BarcodePrintController@autocomplete_search_product');
        Route::get('store-or-update', 'BarcodePrintController@store_or_update_data')->name('store.or.update');
        Route::get('store-or-update-range', 'BarcodePrintController@store_or_update_data_range')->name('store.or.update.range');
        Route::post('barcode/search-product', 'BarcodePrintController@search_product')->name('barcode.search.product');
        Route::get('print-barcode', 'BarcodePrintController@index')->name('print.barcode');
        Route::post('generate-barcode', 'BarcodePrintController@generatebarcode')->name('generate.barcode');
    });
});