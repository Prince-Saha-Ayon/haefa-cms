<?php

namespace Modules\Patient\Entities;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Patient\Entities\Gender;
use Modules\Patient\Entities\MaritalStatus;
use Modules\Patient\Entities\Address;
use Modules\Patient\Entities\SelfType;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Prescription\Entities\Prescription;

class Patient extends BaseModel
{
    protected $table = 'Patient';
    public $timestamps = false;
    protected $fillable = ['PatientId', 'WorkPlaceId', 'WorkPlaceBranchId','PatientCode',
        'RegistrationId','GivenName','FamilyName','GenderId','BirthDate','Age','AgeYear',
        'AgeMonth','AgeDay','JoiningDate','ReligionId','RefDepartmentId','RefDesignationId',
        'MaritalStatusId','EducationId','FatherName','MotherName','SpouseName','HeadOfFamilyId',
        'IdNumber','CellNumber','FamilyMembers','ChildrenNumber','ChildAge0To1','ChildAge1To5',
        'ChildAgeOver5','EmailAddress','PatientImage','Status','CreateDate', 'CreateUser',
        'UpdateDate', 'UpdateUser', 'OrgId'];
    protected $order = ['CreateDate'=>'desc'];

    protected $name;
    public $incrementing = false;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function Gender()
    {
        return $this->hasOne(Gender::class, 'GenderId', 'GenderId')->select('GenderId','GenderCode');
    }
    public function MaritalStatus()
    {
        return $this->hasOne(MaritalStatus::class, 'MaritalStatusId', 'MaritalStatusId')->select('MaritalStatusId','MaritalStatusCode');
    }
    public function self_type()
    {
        return $this->hasOne(SelfType::class,'HeadOfFamilyId', 'IdOwner')->select('HeadOfFamilyId','HeadOfFamilyCode');
    }
    public function address()
    {
        return $this->hasOne(Address::class,'PatientId', 'PatientId');
    }

    public function prescription()
    {
      return $this->belongsTo(Prescription::class, 'PatientId', 'PatientId');
    }


    private function get_datatable_query()
    {
        if(permission('patient-bulk-delete')){
            $this->column_order = [null,'PatientId','name','status',null];
        }else{
            $this->column_order = ['PatientId','name','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('RegistrationId',$this->name);
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }

    public static function get_branch_name(){
        $login_user = Auth::user()->cc_id ?? '';
        $branch_query = DB::select("SELECT TOP 1 hc.HealthCenterName
            FROM Patient p
            INNER JOIN barcode_formats bf ON LEFT(p.RegistrationId, 9) = bf.barcode_prefix
            INNER JOIN HealthCenter hc ON bf.barcode_community_clinic = hc.HealthCenterId
            INNER JOIN users u ON u.cc_id = bf.id
            WHERE u.cc_id = '$login_user'");

        $branch_name = $branch_query[0]->HealthCenterName??'';
        return $branch_name;

    }
    public static function get_branch_id(){
        $login_user = Auth::user()->cc_id ?? '';
        $branch_query = DB::select("SELECT TOP 1 hc.HealthCenterId
            FROM Patient p
            INNER JOIN barcode_formats bf ON LEFT(p.RegistrationId, 9) = bf.barcode_prefix
            INNER JOIN HealthCenter hc ON bf.barcode_community_clinic = hc.HealthCenterId
            INNER JOIN users u ON u.cc_id = bf.id
            WHERE u.cc_id = '$login_user'");
        $branch_id = $branch_query[0]->HealthCenterId??'';
        return $branch_id;

    }
    //get branch wise disease
    public static function get_branch_wise_disease_count()
    {
        $LoginRegistrationId = Patient::registration_ids();
        $disease_array = [
            'DBB019E4-E1A1-460F-A874-C98101D006FB',
            '81209B1C-8C0D-414C-A5ED-3D179F3B463A',
            '98E2AE4F-7639-49CA-A7AF-9FE396F5EDC2',
            'BB268EAB-EDD6-4D50-8886-C418C133C555',
            '0C436780-E230-4A61-8B9C-C111CF294539'
        ];

        $today = Carbon::today(); // $yesterday = $today->subDay();
        $startDate = $today->format('m/d/Y');

        $all_diseases = DB::table('MDataPatientIllnessHistory')
            ->selectRaw('COUNT(*) as count, RefIllness.IllnessId, RefIllness.IllnessCode')
            ->Join('RefIllness', 'RefIllness.IllnessId', '=', 'MDataPatientIllnessHistory.IllnessId')
            ->Join('Patient', 'Patient.PatientId', '=', 'MDataPatientIllnessHistory.PatientId')
            ->whereIn('Patient.RegistrationId', $LoginRegistrationId)
            ->whereIn('RefIllness.IllnessId', $disease_array)
            ->whereDate('MDataPatientIllnessHistory.CreateDate', $startDate)
            ->groupBy('RefIllness.IllnessId', 'RefIllness.IllnessCode')
            ->get();

        return $all_diseases;
    }

    //get branch wise referred case with referrel center
    public static function branch_wise_referred_case_with_referrel_center_count(){
        $branch_id = Patient::get_branch_id();

        $today = Carbon::today();
        $startDate = $today->format('m/d/Y');

        $all_referred_case = DB::table('MDataPatientReferral')
            ->select(DB::raw('COUNT(*) as number_of_referred_case'),'HealthCenter.HealthCenterName')
            ->join('HealthCenter', 'HealthCenter.HealthCenterId', '=', 'MDataPatientReferral.HealthCenterId')
            ->join('Patient', 'Patient.PatientId', '=', 'MDataPatientReferral.PatientId')
            ->where('HealthCenter.HealthCenterId', $branch_id)
            ->whereDate('MDataPatientReferral.CreateDate', $startDate)
            ->groupBy('HealthCenter.HealthCenterName')
            ->orderByRaw('COUNT(*) DESC')
            ->get();
        return $all_referred_case;
    }

    //get referred
    public static function registration_ids(){
        $login_user = Auth::user()->cc_id ?? '';
        // Get RegistrationIds

        $data = DB::select("SELECT p.RegistrationId
            FROM Patient p
            INNER JOIN barcode_formats bf ON LEFT(p.RegistrationId, 9) = bf.barcode_prefix
            INNER JOIN users u ON u.cc_id = bf.id
            WHERE u.cc_id = '$login_user'");

        $LoginRegistrationId = array_map(function ($item) {
            return $item->RegistrationId;
        }, $data);
        return $LoginRegistrationId;
    }

    //top ten disease based on patient
    public static function top_ten_disease(){
        $LoginRegistrationId = Patient::registration_ids();
        $today = Carbon::today(); // $yesterday = $today->subDay();
        $startDate = $today->format('m/d/Y');

        $illnesses = DB::table('MDataPatientIllnessHistory')
            ->join('RefIllness', 'MDataPatientIllnessHistory.IllnessId', '=', 'RefIllness.IllnessId')
            ->join('Patient', 'MDataPatientIllnessHistory.PatientId', '=', 'Patient.PatientId')
            ->whereDate('MDataPatientIllnessHistory.CreateDate', $startDate)
            ->whereIn('Patient.RegistrationId', $LoginRegistrationId)
            ->groupBy('RefIllness.IllnessId', 'RefIllness.IllnessCode')
            ->orderByRaw('COUNT(*) DESC')
            ->select('RefIllness.IllnessId', 'RefIllness.IllnessCode', DB::raw('COUNT(*) as Patients'))
            ->take(10)  // You can add this back if you want to limit the results to 10 rows
            ->get();
        return $illnesses;
    }

    //all disease based on patient start
    public static function all_disease(){
        $LoginRegistrationId = Patient::registration_ids();
        $today = Carbon::today();
        $yesterday = $today->subDay();
        $startDate = $today->format('m/d/Y');

        $illnesses = DB::table('MDataPatientIllnessHistory')
            ->join('RefIllness', 'MDataPatientIllnessHistory.IllnessId', '=', 'RefIllness.IllnessId')
            ->join('Patient', 'MDataPatientIllnessHistory.PatientId', '=', 'Patient.PatientId')
            ->whereDate('MDataPatientIllnessHistory.CreateDate', $startDate)
            ->whereIn('Patient.RegistrationId', $LoginRegistrationId)
            ->groupBy('RefIllness.IllnessId', 'RefIllness.IllnessCode')
            ->orderByRaw('COUNT(*) DESC')
            ->select('RefIllness.IllnessId', 'RefIllness.IllnessCode', DB::raw('COUNT(*) as Patients'))
            ->get();
        return $illnesses;
    }
    //all disease based on patient end
}
