<?php

namespace Modules\RefProvisionalDiagnosis\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Entities\BaseModel;
use Modules\RefProvisionalDiagnosisGroup\Entities\RefProvisionalDiagnosisGroup;


class RefProvisionalDiagnosis extends BaseModel
{
    protected $table = 'RefProvisionalDiagnosis';
    // protected $primaryKey = 'GenderId';
    public $timestamps = false;

    protected $fillable = ['RefProvisionalDiagnosisId','RefProvisionalDiagnosisGroupId','ProvisionalDiagnosisCode','ProvisionalDiagnosisName','Description','GroupSortOrder','SortOrder','Status',
    'CreateDate','CreateUser','UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function diagnosisgroup(){
        return $this->BelongsTo(RefProvisionalDiagnosisGroup::class,'RefProvisionalDiagnosisGroupId','RefProvisionalDiagnosisGroupId');
    }
  

    
    private function get_datatable_query()
    {
        if(permission('patient-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'RefProvisionalDiagnosisId','RefProvisionalDiagnosisGroupId','ProvisionalDiagnosisCode','ProvisionalDiagnosisName','Description','GroupSortOrder','SortOrder','Status',null];
        }else{
            $this->column_order = ['RefProvisionalDiagnosisId','RefProvisionalDiagnosisGroupId','ProvisionalDiagnosisCode','ProvisionalDiagnosisName','Description','GroupSortOrder','SortOrder','Status',null];
        }

        // $query = self::toBase();
        $query = self::with(['diagnosisgroup']);

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('ProvisionalDiagnosisCode','like', '%'.$this->name.'%');
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
