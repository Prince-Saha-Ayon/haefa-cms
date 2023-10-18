<?php

namespace Modules\RefLabInvestigation\Entities;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Entities\BaseModel;
use Modules\RefLabInvestigationGroup\Entities\RefLabInvestigationGroup;
use Modules\RefProvisionalDiagnosisGroup\Entities\RefProvisionalDiagnosisGroup;


class RefLabInvestigation extends BaseModel
{
    protected $table = 'RefLabInvestigation';
    // protected $primaryKey = 'GenderId';
    public $timestamps = false;

    protected $fillable = ['RefLabInvestigationId','RefLabInvestigationGroupId','RefLabInvestigationCode','Investigation','Description','SortOrder','Status',
    'CreateDate','CreateUser','UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function refLabInvestigationGroup(){
        return $this->BelongsTo(RefLabInvestigationGroup::class,'RefLabInvestigationGroupId','RefLabInvestigationGroupId');
    }
  

    
    private function get_datatable_query()
    {
        if(permission('patient-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'RefLabInvestigationId','RefLabInvestigationGroupId','RefLabInvestigationCode','Investigation','Description','SortOrder','Status',null];
        }else{
            $this->column_order = ['RefLabInvestigationId','RefLabInvestigationGroupId','RefLabInvestigationCode','Investigation','Description','SortOrder','Status',null];
        }

        // $query = self::toBase();
        $query = self::with(['refLabInvestigationGroup']);

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('RefLabInvestigationCode','like', '%'.$this->name.'%');
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
