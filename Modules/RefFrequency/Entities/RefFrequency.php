<?php

namespace Modules\RefFrequency\Entities;

use Modules\Base\Entities\BaseModel;

class RefFrequency extends BaseModel
{
    protected $table = 'RefFrequency';
    // protected $primaryKey = 'GenderId';
    public $timestamps = false;

    protected $fillable = ['FrequencyId','FrequencyCode','FrequencyInEnglish','FrequencyInBangla','Description','SortOrder','Status','CreateDate','CreateUser','UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    private function get_datatable_query()
    {
        if(permission('refduration-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'FrequencyCode','FrequencyInEnglish','FrequencyInBangla','Description','Status',null];
        }else{
            $this->column_order = ['FrequencyCode','FrequencyInEnglish','FrequencyInBangla','Description','Status',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('FrequencyCode','like', '%'.$this->name.'%');
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
