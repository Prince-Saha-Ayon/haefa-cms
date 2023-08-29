<?php

namespace Modules\Employee\Entities;

use Modules\Base\Entities\BaseModel;

class Employee extends BaseModel
{
    protected $table = 'Employee';
    public $timestamps = false;

    protected $fillable = ['EmployeeId','OrgId','EmployeeCode','RegistrationNumber','FirstName'
    ,'LastName','GenderId','BirthDate','JoiningDate','MaritalStatusId','EducationId','Designation'
    ,'ReligionId','RoleId','Email','Phone','NationalIdNumber','EmployeeImage','EmployeeSignature',
    'Status','CreateUser','CreateDate','UpdateUser','UpdateDate'];

    protected $order = ['CreateDate'=>'desc'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('refdesignation-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'EmployeeCode','RegistrationNumber','FirstName','LastName',null];
        }else{
            $this->column_order = ['EmployeeCode','RegistrationNumber','FirstName','LastName',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //
        if (!empty($this->name)) {
            $query->where('EmployeeCode','like', '%'.$this->name.'%');
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
        try {
            if ($this->lengthVlaue != -1) {
                $query->offset($this->startVlaue)->limit($this->lengthVlaue);
            }
            return $query->get();
        } catch (\Exception $e) {
            dd($e->getMessage()); // Display only the error message
        }
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
