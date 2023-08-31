<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Patient\Entities\Patient;
use Modules\Report\Entities\Report;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    public function __construct(Report $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $this->setPageData('Patient Age Count Report','Patient Age Count Report','fas fa-th-list');
        return view('report::index');
    }
    public function datewisedxindex(){
        $this->setPageData('Datewise Provisional DX','Datewise Provisional DX','fas fa-th-list');
        return view('report::datewisedx');
    }
    public function diseaseindex()
    {
        $this->setPageData('Patient Age Count Report','Patient Age Count Report','fas fa-th-list');
        return view('report::diseasechart');
    }
    public function glucosegraphindex(){
        $registrationId=Patient::select('RegistrationId')->get();
        $this->setPageData('Glucose Graph Report','Glucose Graph Report','fas fa-th-list');
        return view('report::glucosegraph',compact('registrationId'));

    }

    public function SearchByDate(Request $request){
        $starting_date = $request->starting_date;
        $ending_date = $request->ending_date;

        $results = DB::table("MDataProvisionalDiagnosis")
            ->select(DB::raw("CAST(CreateDate AS DATE) as CreateDate"), 'ProvisionalDiagnosis', DB::raw('COUNT(*) as Total'))
            ->whereDate('CreateDate', '>=', $starting_date)
            ->whereDate('CreateDate', '<=', $ending_date)
            ->groupBy(DB::raw("CAST(CreateDate AS DATE)"), 'ProvisionalDiagnosis')
            ->get();


        $this->setPageData('Datewise Provisional DX','Datewise Provisional DX','fas fa-th-list');
        return view('report::datewisedx',compact('results'));

    }
    public function SearchByAge(Request $request){
        $starting_age = $request->starting_age;
        $ending_age = $request->ending_age;
        $male=$female=$maleBelowFive=$maleAboveFive=$femaleBelowFive=$femaleAboveFive=$Total=0;
        $results = DB::table("Patient")
            ->whereRaw('CONVERT(float, Age) >= ?', [$starting_age])
            ->whereRaw('CONVERT(float, Age) <= ?', [$ending_age])
            ->join('MDataProvisionalDiagnosis', 'Patient.PatientId', '=', 'MDataProvisionalDiagnosis.PatientId')
            ->join('RefGender', 'Patient.GenderId', '=', 'RefGender.GenderId')
            ->get(['ProvisionalDiagnosis', 'OtherProvisionalDiagnosis', 'GivenName', 'FamilyName', 'Age', 'GenderCode']);
        foreach ($results as $result){
            if ($result->Age < 6 && $result->GenderCode == 'Male'){
                $maleBelowFive++;
            }
            if ($result->Age > 5 && $result->GenderCode == 'Male'){
                $maleAboveFive++;
            }
            if ($result->Age < 6 && $result->GenderCode == 'Female'){
                $femaleBelowFive++;
            }
            if ($result->Age > 5 && $result->GenderCode == 'Female'){
                $femaleAboveFive++;
            }
            if ($result->GenderCode == 'Male'){
                $male++;
            }
            if ($result->GenderCode == 'Female'){
                $female++;
            }

        }
        $Total=$male+$female;


        $this->setPageData(
            'Report-'. str_repeat(' ', 2).
            'Total: '. $Total .','.  str_repeat(' ', 2).
            'Male: '. $male .','.  str_repeat(' ', 2)  .
            'Female: '.$female.','. str_repeat(' ', 2) .
            'Male 0-5: '. $maleBelowFive .','. str_repeat(' ', 2) .
            'Male above 5: '. $maleAboveFive .','. str_repeat(' ', 2) .
            'Female 0-5: '. $femaleBelowFive .','. str_repeat(' ', 2) .
            'Female above 5: '. $femaleAboveFive,
            'Patient Age Count Report',
            'fas fa-th-list'
        );

        return view('report::index',compact('results','male','Total','female','maleAboveFive','maleBelowFive','femaleAboveFive','femaleBelowFive'));

    }

    public function branchWisePatients(Request $request){
        $daterange = $request->daterange;
        $dates = explode(' - ', $daterange);
// Assign the start and end dates to separate variables
        $first_date = $dates[0]??'';
        $last_date = $dates[1]??'';

        $barcode_prefix = $request->patient_id??"";

        $male=$female=$Total=$femaleAboveFive=0;

        //branches
        $branches = DB::table('barcode_formats')
            ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
            ->select('barcode_formats.barcode_prefix', 'HealthCenter.HealthCenterName')
            ->get();

        $results = DB::table('barcode_formats')
            ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
            ->join('Patient', function ($join) use ($first_date, $last_date, $barcode_prefix) {
                // Cast uniqueidentifier to string and then extract the first nine characters
                $join->on(DB::raw('SUBSTRING(CONVERT(VARCHAR(36), Patient.RegistrationId), 1, 9)'), '=', 'barcode_formats.barcode_prefix')
                    ->whereBetween('Patient.CreateDate', [$first_date, $last_date]);
                if ($barcode_prefix) {
                    $join->Where('barcode_formats.barcode_prefix', $barcode_prefix);
                }
            })
            ->join('RefGender','RefGender.GenderId','=','Patient.GenderId')
            ->get();

        if($barcode_prefix){
            $barcode_tbl = DB::table('barcode_formats')
                ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
                ->where('barcode_formats.barcode_prefix',$barcode_prefix)
                ->first();
            $branchName = $barcode_tbl->HealthCenterName??'';
        }else{
            $branchName = '';
        }

        foreach ($results as $result){
            if ($result->GenderCode == 'Male'){
                $male++;
            }
            if ($result->GenderCode == 'Female'){
                $female++;
            }
        }

        $Total=$male+$female;

        $this->setPageData(
            'Report-'. str_repeat(' ', 2).
            'Total: '. $Total .','.  str_repeat(' ', 2).
            'Male: '. $male .','.  str_repeat(' ', 2)  .
            'Female: '.$female.','. str_repeat(' ', 2) .
            'Branch Name: '. $branchName,
            'Branch Wise Patient Report',
            'fas fa-th-list'
        );

        return view('report::branchwisepatients',compact('branches','branchName','results','male','Total','female'));
    }

    public function diseaseRateDateRange(Request $request){
        $daterange = $request->daterange;
        $dates = explode(' - ', $daterange);
        $first_date = $dates[0]??'';
        $last_date = $dates[1]??'';

        $barcode_prefix = $request->patient_id??"";
        $male=$female=$Total=$femaleAboveFive=0;

        //branches
        $branches = DB::table('barcode_formats')
            ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
            ->select('barcode_formats.barcode_prefix', 'HealthCenter.HealthCenterName')
            ->get();

        $results = DB::table('barcode_formats')
            ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
            ->join('Patient', function ($join) use ($first_date, $last_date, $barcode_prefix) {
                // Cast uniqueidentifier to string and then extract the first nine characters
                $join->on(DB::raw('SUBSTRING(CONVERT(VARCHAR(36), Patient.RegistrationId), 1, 9)'), '=', 'barcode_formats.barcode_prefix')
                    ->whereBetween('Patient.CreateDate', [$first_date, $last_date]);
                if ($barcode_prefix) {
                    $join->Where('barcode_formats.barcode_prefix', $barcode_prefix);
                }
            })
            ->join('RefGender','RefGender.GenderId','=','Patient.GenderId')
            ->get();

        if($barcode_prefix){
            $barcode_tbl = DB::table('barcode_formats')
                ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
                ->where('barcode_formats.barcode_prefix',$barcode_prefix)
                ->first();
            $branchName = $barcode_tbl->HealthCenterName??'';
        }else{
            $branchName = '';
        }

        foreach ($results as $result){
            if ($result->GenderCode == 'Male'){
                $male++;
            }
            if ($result->GenderCode == 'Female'){
                $female++;
            }
        }

        $Total=$male+$female;

        $this->setPageData(
            'Report-'. str_repeat(' ', 2).
            'Total: '. $Total .','.  str_repeat(' ', 2).
            'Male: '. $male .','.  str_repeat(' ', 2)  .
            'Female: '.$female.','. str_repeat(' ', 2) .
            'Branch Name: '. $branchName,
            'Branch Wise Disease Report',
            'fas fa-th-list'
        );

        return view('report::diseaserate_by_daterange',compact('branches','branchName','results','male','Total','female'));
    }

    public function AjaxDiseaseRateDateRange(Request $request){
//          dd($request->all());
        $barcode_prefix = $request->hc_id;
        $first_date = $request->fdate;
        $last_date = $request->ldate;

        if($barcode_prefix){
            $barcode_tbl = DB::table('barcode_formats')
                ->join('HealthCenter', 'barcode_formats.barcode_community_clinic', '=', 'HealthCenter.HealthCenterId')
                ->where('barcode_formats.barcode_prefix',$barcode_prefix)
                ->first();
            $branchName = $barcode_tbl->HealthCenterName??'';
        }else{
            $branchName = '';
        }

        $illnesses = DB::table('MDataPatientIllnessHistory')
            ->join('RefIllness', 'MDataPatientIllnessHistory.IllnessId', '=', 'RefIllness.IllnessId')
            ->join('Patient', 'MDataPatientIllnessHistory.PatientId', '=', 'Patient.PatientId')
            ->whereBetween('Patient.CreateDate', [$first_date, $last_date])
            ->where('Patient.RegistrationId', 'LIKE', $barcode_prefix . '%')
            ->groupBy('RefIllness.IllnessId', 'RefIllness.IllnessCode')
            ->orderByRaw('COUNT(*) DESC')
            ->select('RefIllness.IllnessId', 'RefIllness.IllnessCode', DB::raw('COUNT(*) as Patients'))
            ->take(10)
            ->get();

        $data = [
            'data' => [],
        ];

        foreach ($illnesses as $datat) {
            $data['data'][] = [$datat->IllnessCode ?? '', (int)$datat->Patients ?? 0];
        }
        $response = [
            'healthcenter' => $branchName,
            'data' => $data,
        ];
        return response()->json($response);
    }

    public function GlucoseGraph(Request $request){
        $registrationId=Patient::select('RegistrationId')->get();
        $starting_date = $request->starting_date;
        $ending_date = $request->ending_date;
        $RegistrationId = $request->reg_id;

        $results = DB::select("
            SELECT TOP 7 CONVERT(date, MDataGlucoseHb.CreateDate) AS DistinctDate, RBG, FBG,Hemoglobin
            FROM MDataGlucoseHb
            INNER JOIN Patient ON Patient.PatientId=MDataGlucoseHb.PatientId AND Patient.RegistrationId='{$RegistrationId}'
            WHERE CONVERT(date, MDataGlucoseHb.CreateDate) BETWEEN ? AND ? AND RBG !='' AND FBG !=''
            GROUP BY CONVERT(date, MDataGlucoseHb.CreateDate), RBG, FBG,Hemoglobin
            ORDER BY DistinctDate DESC
        ", [$starting_date, $ending_date]);

        $rbg = array();
        $fbg = array();
        $hemoglobin = array();
        $DistinctDate = array();

        foreach ($results as $result) {
            array_push($rbg, $result->RBG);
            array_push($fbg, $result->FBG);
            array_push($hemoglobin, $result->Hemoglobin);
            array_push($DistinctDate, $result->DistinctDate);
        }

        $rbgNumeric = json_encode($rbg,JSON_NUMERIC_CHECK);
        $fbgNumeric = json_encode($fbg, JSON_NUMERIC_CHECK);
        $hemoglobinNumeric = json_encode($hemoglobin, JSON_NUMERIC_CHECK);


//        return response()->json([
//            's_date'=>$rbgNumeric,
//            'e_date'=>$fbgNumeric,
//            'reg_id'=>$rbg,
//            'results'=>$DistinctDate,
//        ]);
        $this->setPageData('Glucose Graph Report','Glucose Graph Report','fas fa-th-list');
        return view('report::glucosegraph',compact('DistinctDate','registrationId','rbg','rbgNumeric','fbg','fbgNumeric','hemoglobin','hemoglobinNumeric'));
    }

     /**
     * Patient blood pressure Search form load.
     * @return Renderable
     */

    public function PatientBloodPressureGraph(){
        $registrationId=Patient::select('RegistrationId')->get();
        $this->setPageData('Patient Blood Pressure Graph','Patient wise Blood Pressure Graph','fas fa-th-list');

        return view('report::patientbloodpressuregraph',compact('registrationId'));
    }

     /**
     * Patient blood pressure Ajax graph with data load.
     * @return Renderable
     */

    public function AjaxPatientBloodPressure(Request $request){
        $startDate = $request->starting_date;
        $endDate = $request->ending_date;
        $RegistrationId = $request->registration_id;

        $datas = DB::select("
            SELECT TOP 7 CONVERT(date, MDataBP.CreateDate) AS DistinctDate, BPSystolic1, BPDiastolic1, BPSystolic2, BPDiastolic2
            FROM MDataBP
            INNER JOIN Patient ON Patient.PatientId=MDataBP.PatientId AND Patient.RegistrationId='{$RegistrationId}'
            WHERE CONVERT(date, MDataBP.CreateDate) BETWEEN ? AND ? AND BPSystolic1 !='' AND BPSystolic2 !=''
            AND BPSystolic2 !='' AND BPDiastolic2 !=''
            GROUP BY CONVERT(date, MDataBP.CreateDate), BPSystolic1, BPDiastolic1, BPSystolic2, BPDiastolic2
            ORDER BY DistinctDate DESC
        ", [$startDate, $endDate]);

        $BPSystolic1 = array();
        $BPDiastolic1 = array();
        $BPSystolic2 = array();
        $BPDiastolic2 = array();
        $DistinctDate = array();

        foreach ($datas as $row) {
            array_push($BPSystolic1, $row->BPSystolic1);
            array_push($BPDiastolic1, $row->BPDiastolic1);
            array_push($BPSystolic2, $row->BPSystolic2);
            array_push($BPDiastolic2, $row->BPDiastolic2);
            array_push($DistinctDate, $row->DistinctDate);
        }

        $BPSystolic1Numeric = json_encode($BPSystolic1,JSON_NUMERIC_CHECK);
        $BPDiastolic1Numeric = json_encode($BPDiastolic1, JSON_NUMERIC_CHECK);
        $BPSystolic2Numeric = json_encode($BPSystolic2, JSON_NUMERIC_CHECK);
        $BPDiastolic2Numeric = json_encode($BPDiastolic2, JSON_NUMERIC_CHECK);
        return view('report::bloodpressure_ajax',compact('BPSystolic1Numeric','BPDiastolic1Numeric',
        'BPSystolic2Numeric','BPDiastolic2Numeric','DistinctDate','BPSystolic1','BPDiastolic1',
        'BPSystolic2','BPDiastolic2'));
    }

    /**
     * Hear rate Search form load.
     * @return Renderable
     */
    public function HeartRateGraph(){
        $registrationId=Patient::select('RegistrationId')->get();
        $this->setPageData('Hear Rate Graph','Hear Rate Graph','fas fa-th-list');

        return view('report::heartrategraph',compact('registrationId'));
    }

    /**
     * Hear Rate Ajax load graph with data.
     * @return Renderable
     */

     public function AjaxHeartRateGraph(Request $request){
        $startDate = $request->starting_date;
        $endDate = $request->ending_date;
        $RegistrationId = $request->registration_id;

        $datas = DB::select("SELECT TOP 7 CONVERT(DATE, MDataBP.CreateDate) AS DistinctDate, MDataBP.HeartRate
        FROM MDataBP
        INNER JOIN Patient ON Patient.PatientId=MDataBP.PatientId AND Patient.RegistrationId='{$RegistrationId}'
        WHERE CONVERT(DATE, MDataBP.CreateDate) BETWEEN ? AND ? AND MDataBP.HeartRate !=''
        GROUP BY CONVERT(DATE, MDataBP.CreateDate), MDataBP.HeartRate
        ORDER BY DistinctDate DESC", [$startDate, $endDate]);

        $HeartRate1 = array();
        $DistinctDate = array();

        foreach ($datas as $row) {
            array_push($HeartRate1, $row->HeartRate);
            array_push($DistinctDate, $row->DistinctDate);
        }

        $HeartRate1Numeric = json_encode($HeartRate1,JSON_NUMERIC_CHECK);
        return view('report::heartrategraph_ajax',compact('HeartRate1Numeric','DistinctDate','HeartRate1'));
    }

    /**
     * Temperature Graph Search form load.
     * @return Renderable
     */
    public function TemperatureGraph(){
        $registrationId=Patient::select('RegistrationId')->get();
        $this->setPageData('Temperature Graph','Temperature Graph','fas fa-th-list');
        return view('report::temperaturegraph',compact('registrationId'));
    }

    /**
     * Temperature Ajax load graph with data.
     * @return Renderable
     */

     public function AjaxTemperatureGraph(Request $request){
        $startDate = $request->starting_date;
        $endDate = $request->ending_date;
        $RegistrationId = $request->registration_id;

        $datas = DB::select("SELECT TOP 7 CONVERT(DATE, MDataBP.CreateDate) AS DistinctDate, MDataBP.CurrentTemparature
        FROM MDataBP
        INNER JOIN Patient ON Patient.PatientId=MDataBP.PatientId AND Patient.RegistrationId='{$RegistrationId}'
        WHERE CONVERT(DATE, MDataBP.CreateDate) BETWEEN ? AND ? AND MDataBP.CurrentTemparature !=''
        GROUP BY CONVERT(DATE, MDataBP.CreateDate), MDataBP.CurrentTemparature
        ORDER BY DistinctDate DESC", [$startDate, $endDate]);

        $CurrentTemparature1 = array();
        $DistinctDate = array();

        foreach ($datas as $row) {
            array_push($CurrentTemparature1, $row->CurrentTemparature);
            array_push($DistinctDate, $row->DistinctDate);
        }

        $CurrentTemparature1Numeric = json_encode($CurrentTemparature1,JSON_NUMERIC_CHECK);
        return view('report::temperaturegraph_ajax',compact('CurrentTemparature1Numeric','DistinctDate','CurrentTemparature1'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('report::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('report::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('report::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function generateReport(Request $request)
    {
        $areaId = $request->input('area_id');

        $topDiseases = Disease::select('name', DB::raw('COUNT(*) as occurrence'))
            ->where('area_id', $areaId)
            ->groupBy('name')
            ->orderByDesc('occurrence')
            ->limit(10)
            ->get();

        return view('reports.disease_pie_chart', compact('topDiseases'));
    }
}
