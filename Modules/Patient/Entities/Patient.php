<?php

namespace Modules\Patient\Entities;
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
    protected $fillable = ['PatientId', 'WorkPlaceId', 'WorkPlaceBranchId','PatientCode','RegistrationId','GivenName','FamilyName','GenderId','BirthDate','Age','AgeYear','AgeMonth','AgeDay','JoiningDate','ReligionId','RefDepartmentId','RefDesignationId','MaritalStatusId','EducationId','FatherName','MotherName','SpouseName','HeadOfFamilyId','IdNumber','CellNumber','FamilyMembers','ChildrenNumber','ChildAge0To1','ChildAge1To5','ChildAgeOver5','EmailAddress','PatientImage','Status','CreateDate', 'CreateUser', 'UpdateDate', 'UpdateUser', 'OrgId'];
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
}
