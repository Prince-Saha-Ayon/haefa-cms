<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
            $this->setPageData('Dashboard','Dashboard','fas fa-tachometer-alt');

            $branch_wise_disease_count = Patient::get_branch_wise_disease_count();

            $referred_case_count_heltcenter = Patient::branch_wise_referred_case_with_referrel_center_count();
            $branch_name = Patient::get_branch_name();
            $registrationId=Patient::select('RegistrationId')->get();
            //top ten disease graph of todays date start
            $illnesses['diseases'] = Patient::top_ten_disease();
            //top ten disease graph of todays date end

            //all disease graph of todays date start
            $all_illnesses = Patient::all_disease();

            //all disease graph of todays date end
            return view('home',compact('registrationId','illnesses','all_illnesses',
                'branch_name','branch_wise_disease_count','referred_case_count_heltcenter'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    // public function dashboard_data()
    // {
    //     if($start_date && $end_date)
    //     {
    //         $patient = Patient::get()->count();

    //         // $purchase = Purchase::toBase()->whereDate('created_at','>=',$start_date)
    //         // ->whereDate('created_at','<=',$end_date)->sum('grand_total');

    //         // $customer = Customer::toBase()->whereDate('created_at','>=',$start_date)
    //         // ->whereDate('created_at','<=',$end_date)->get()->count();

    //         // $supplier = Supplier::toBase()->whereDate('created_at','>=',$start_date)
    //         // ->whereDate('created_at','<=',$end_date)->get()->count();

    //         // $expense = Expense::toBase()->whereDate('created_at','>=',$start_date)
    //         // ->whereDate('created_at','<=',$end_date)->sum('amount');

    //         $data = [
    //             'sale' => number_format($sale,2,'.',','),
    //             'patient' => $patient,
    //             'profit' => number_format(($sale - $purchase),2,'.',','),
    //             'customer' => $customer,
    //             'supplier' => $supplier,
    //             'expense' => number_format($expense,2,'.',','),
    //         ];

    //         return response()->json($data);
    //     }
    // }

    public function unauthorized()
    {
        $this->setPageData('Unathorized','Unathorized','fas fa-ban');
        return view('unauthorized');
    }
}
