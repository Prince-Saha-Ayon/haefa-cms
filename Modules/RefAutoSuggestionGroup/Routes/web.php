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
    Route::get('refautosuggestiongroup','RefAutoSuggestionGroupController@index')->name('menu');
    Route::group(['prefix' => 'refautosuggestiongroup', 'as'=>'refautosuggestiongroup.'], function () {
        Route::post('datatable-data', 'RefAutoSuggestionGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefAutoSuggestionGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefAutoSuggestionGroupController@edit')->name('edit');
        Route::post('show', 'RefAutoSuggestionGroupController@show')->name('show');
        Route::post('delete', 'RefAutoSuggestionGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'RefAutoSuggestionGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefAutoSuggestionGroupController@change_status')->name('change.status');
    });
});

