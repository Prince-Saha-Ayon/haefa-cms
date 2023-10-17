<?php

namespace Modules\RefCancerTreatment\Entities;

use Modules\Base\Entities\BaseModel;

class RefCancerTreatment extends BaseModel
{
    protected $table = 'RefCancerTreatment';
    // protected $primaryKey = 'GenderId';
    public $timestamps = false;

    protected $fillable = ['CancerTreatmentId','CancerTreatmentCode','Description','SortOrder','Status','CreateDate','CreateUser','UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    private function get_datatable_query()
    {
        if(permission('refcancertreatment-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'CancerTreatmentCode','Description','Status',null];
        }else{
            $this->column_order = ['CancerTreatmentCode','Description','Status',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('CancerTreatmentCode','like', '%'.$this->name.'%');
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
