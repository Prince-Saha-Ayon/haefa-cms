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
        Route::get('refautosuggestion','RefAutoSuggestionController@index')->name('menu');
        Route::group(['prefix' => 'refautosuggestion', 'as'=>'refautosuggestion.'], function () {
        Route::post('datatable-data', 'RefAutoSuggestionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefAutoSuggestionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefAutoSuggestionController@edit')->name('edit');
        Route::post('show', 'RefAutoSuggestionController@show')->name('show');
        Route::post('delete', 'RefAutoSuggestionController@delete')->name('delete');
        Route::post('bulk-delete', 'RefAutoSuggestionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefAutoSuggestionController@change_status')->name('change.status');
    });
});
