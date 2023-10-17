<?php

namespace Modules\RefProvisionalDiagnosisGroup\Entities;

use Modules\Base\Entities\BaseModel;

class RefProvisionalDiagnosisGroup extends BaseModel
{
    protected $table = 'RefProvisionalDiagnosisGroup';
    // protected $primaryKey = 'GenderId';
    public $timestamps = false;

    protected $fillable = ['RefProvisionalDiagnosisGroupId','RefProvisionalDiagnosisGroupCode','Category','CommonTerm','SortOrder','Status','CreateDate','CreateUser','UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    private function get_datatable_query()
    {
        if(permission('refprovisionaldiagnosisgroup-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'RefProvisionalDiagnosisGroupCode','Category','CommonTerm','Status',null];
        }else{
            $this->column_order = ['RefProvisionalDiagnosisGroupCode','Category','CommonTerm','Status',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('RefProvisionalDiagnosisGroupCode','like', '%'.$this->name.'%');
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
