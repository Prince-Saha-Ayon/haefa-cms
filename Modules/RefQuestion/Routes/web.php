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

//RefQuestionController Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refquestion','RefQuestionController@index')->name('menu');
    Route::group(['prefix' => 'refquestion', 'as'=>'refquestion.'], function () {
        Route::post('datatable-data', 'RefQuestionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefQuestionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefQuestionController@edit')->name('edit');
        Route::get('types', 'RefQuestionController@types')->name('types');
        Route::post('show', 'RefQuestionController@show')->name('show');
        Route::post('delete', 'RefQuestionController@delete')->name('delete');
        Route::post('bulk-delete', 'RefQuestionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefQuestionController@change_status')->name('change.status');
    });
});
