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

// Route::prefix('productionapi')->group(function() {
//     Route::get('/', 'ProductionAPIController@index');
// });
Route::group(['middleware' => ['auth']], function () {
 Route::get('patient-registration', 'PatientRegistrationController@index')->name('patient-registration');
 Route::get('send-patient-registration', 'PatientRegistrationController@register')->name('send-patient-registration');
 Route::get('get-patient-registration', 'PatientRegistrationController@GetCount')->name('get-patient-registration');

//  BP

Route::get('patient-bp', 'PatientBpController@index')->name('patient-bp');
Route::get('get-patient-bp', 'PatientBpController@GetCount')->name('get-patient-bp');
Route::get('send-patient-bp', 'PatientBpController@register')->name('send-patient-bp');

//BS
Route::get('patient-bs', 'PatientBsController@index')->name('patient-bs');
Route::get('get-patient-bs', 'PatientBsController@GetCount')->name('get-patient-bs');
Route::get('send-patient-bs', 'PatientBsController@register')->name('send-patient-bs');
});
