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

//RefQuestionType Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refquestiontype','RefQuestionTypeController@index')->name('menu');
    Route::group(['prefix' => 'refquestiontype', 'as'=>'refquestiontype.'], function () {
        Route::post('datatable-data', 'RefQuestionTypeController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefQuestionTypeController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefQuestionTypeController@edit')->name('edit');
        Route::get('types', 'RefQuestionTypeController@types')->name('types');
        Route::post('show', 'RefQuestionTypeController@show')->name('show');
        Route::post('delete', 'RefQuestionTypeController@delete')->name('delete');
        Route::post('bulk-delete', 'RefQuestionTypeController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefQuestionTypeController@change_status')->name('change.status');
    });
});
