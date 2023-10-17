<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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
    Route::get('patientage', 'ReportController@index')->name('patientage');
    Route::get('datewisedx', 'ReportController@datewisedxindex')->name('datewisedx');
    Route::any('search-by-age', 'ReportController@SearchByAge')->name('search-by-age');
    Route::any('date-wise-dx', 'ReportController@SearchByDate')->name('date-wise-dx');
    Route::get('glucosegraph', 'ReportController@glucosegraphindex')->name('glucosegraph');
    Route::post('glucose-graph', 'ReportController@GlucoseGraph')->name('glucose-graph');
    Route::get('diseasechart', 'ReportController@diseaseindex')->name('diseasechart');
    Route::get('patient-blood-pressure-graph', 'ReportController@PatientBloodPressureGraph')->name('patientbloodpressuregraph');
    Route::get('ajax-patient-blood-pressure', 'ReportController@AjaxPatientBloodPressure')->name('ajaxpatientbloodpressure');

    Route::get('heart-rate-graph','ReportController@HeartRateGraph')->name('heartrategraph');
    Route::get('ajax-heart-rate-graph','ReportController@AjaxHeartRateGraph')->name('ajaxheartrategraph');

    Route::get('temperature-graph','ReportController@TemperatureGraph')->name('temperaturegraph');
    Route::get('ajax-temperature-graph','ReportController@AjaxTemperatureGraph')->name('ajaxtemperaturegraph');
    Route::get('disease-wise-patient','ReportController@DiseaseWisePatient')->name('diseasewisepatient');
    Route::any('diseasewise-patient', 'ReportController@SearchByDisease')->name('diseasewise-patient');

    Route::get('hc-wise-referral','ReportController@HCWiseReferral')->name('hcwisereferral');
    Route::any('hcwise-referral', 'ReportController@SearchByHC')->name('hcwise-referral');

    Route::get('top-ten-diseases','ReportController@TopTenDiseases')->name('toptendiseases');
    Route::get('ajax-top-ten-diseases', 'ReportController@AjaxTopTenDiseases')->name('ajaxtoptendiseases');

    Route::any('branch-wise-patients','ReportController@branchWisePatients')->name('branch-wise-patients');
    Route::any('disease-rate-date-range','ReportController@DiseaseRateDateRange')->name('diseaseRateDateRange');
    Route::get('ajax-disease-rate-date-range','ReportController@AjaxDiseaseRateDateRange')->name('AjaxDiseaseRateDateRange');

    Route::get('districtwise-patients','ReportController@DistrictwisePatientIndex')->name('districtwise-patients');
    Route::any('get-districtwise-patients','ReportController@GetDistrictwisePatient')->name('get-districtwise-patients');
    Route::get('get-upazillas/{dcId}','ReportController@GetUpazillas')->name('get-upazillas');
    Route::get('get-unions/{upId}','ReportController@GetUnions')->name('get-unions');


    Route::group(['prefix' => 'patientage', 'as'=>'patientage.'], function () {
        Route::post('show', 'ReportController@show')->name('show');
    });
    Route::any('/serve', function () {
       Artisan::call('execute:master-batch');
    });
    Route::any('data-sync','ReportController@SyncDatabase')->name('executeBatchFile');
    Route::any('data-sync-perform','ReportController@SyncDatabasePerform');
});
