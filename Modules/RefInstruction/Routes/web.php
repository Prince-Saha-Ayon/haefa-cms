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

//RefInstruction Routes
Route::group(['middleware' => ['auth']], function () {
    Route::get('refinstruction','RefInstructionController@index')->name('menu');
    Route::group(['prefix' => 'refinstruction', 'as'=>'refinstruction.'], function () {
        Route::post('datatable-data', 'RefInstructionController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'RefInstructionController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'RefInstructionController@edit')->name('edit');
        Route::post('show', 'RefInstructionController@show')->name('show');
        Route::post('delete', 'RefInstructionController@delete')->name('delete');
        Route::post('bulk-delete', 'RefInstructionController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'RefInstructionController@change_status')->name('change.status');
    });
});
