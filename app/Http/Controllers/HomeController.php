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
        if (permission('dashboard-access')) {
            $this->setPageData('Dashboard','Dashboard','fas fa-tachometer-alt');
            
            $patient_count = Patient::all()->count();
            $doctor_count = User::where('role_id','=',3)->get()->count();
            $patient_today_count = Patient::whereDate('CreateDate', Carbon::today())->get()->count();
            $prescription_total_count = Prescription::all()->count();
            $prescription_today_count = Prescription::whereDate('CreateDate', Carbon::today())->get()->count();
            $registrationId=Patient::select('RegistrationId')->get();

            return view('home',compact('patient_count','doctor_count','patient_today_count','prescription_total_count','prescription_today_count','registrationId'));
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
