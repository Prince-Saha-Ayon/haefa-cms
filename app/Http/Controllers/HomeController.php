<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Patient\Entities\MDataPatientIllnessHistory;
use Modules\Patient\Entities\Patient;
use Modules\Prescription\Entities\Prescription;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    protected function setPageData($page_title,$sub_title,$page_icon)
    {
        view()->share(['page_title'=>$page_title,'sub_title'=>$sub_title,'page_icon'=>$page_icon]);
    }

    public function index()
    {
        ini_set('max_execution_time', 3000);
        if (permission('dashboard-access')) {
            $this->setPageData('Dashboard', 'Dashboard', 'fas fa-tachometer-alt');

            $branch_wise_disease_count = Patient::get_branch_wise_disease_count();

            $DM_count = 0;
            $HTN_count = 0;
            $ANCPNC_count = 0;
            $PregnancyInducedHypertensionCount = 0;
            $GestationalDMCount = 0;

            foreach($branch_wise_disease_count as $branch_wise_disease){
                if($branch_wise_disease->IllnessId=='DBB019E4-E1A1-460F-A874-C98101D006FB'){
                    $DM_count = $branch_wise_disease->count??0;
                }
                else if($branch_wise_disease->IllnessId=='81209B1C-8C0D-414C-A5ED-3D179F3B463A'){
                    $HTN_count = $branch_wise_disease->count??0;
                }
                else if($branch_wise_disease->IllnessId=='98E2AE4F-7639-49CA-A7AF-9FE396F5EDC2'){
                    $ANCPNC_count = $branch_wise_disease->count??0;
                }
                else if($branch_wise_disease->IllnessId=='BB268EAB-EDD6-4D50-8886-C418C133C555'){
                    $PregnancyInducedHypertensionCount = $branch_wise_disease->count??0;
                }
                else if($branch_wise_disease->IllnessId=='0C436780-E230-4A61-8B9C-C111CF294539'){
                    $GestationalDMCount = $branch_wise_disease->count??0;
                }
            }

            $referred_case_count_heltcenter = Patient::branch_wise_referred_case_with_referrel_center_count();

            $branch_name = Patient::get_branch_name();
            $registrationId=Patient::select('RegistrationId')->get();
            //top ten disease graph of todays date start
            $illnesses['diseases'] = Patient::top_ten_disease();

            //top ten disease graph of todays date end

            //all disease graph of todays date start
            $all_illnesses = Patient::all_disease();
            // all disease graph of todays date end
            return view('home',compact('registrationId','illnesses','all_illnesses',
                'branch_name','branch_wise_disease_count','referred_case_count_heltcenter','DM_count',
                'HTN_count','ANCPNC_count','PregnancyInducedHypertensionCount','GestationalDMCount'
            ));

//            $illnesses['diseases']='';
//            $all_illnesses = '';
//            return view('home',compact('illnesses','all_illnesses'));
        }else{
            return $this->unauthorized_access_blocked();
        }

    }

    public function unauthorized()
    {
        $this->setPageData('Unathorized','Unathorized','fas fa-ban');
        return view('unauthorized');
    }
}
