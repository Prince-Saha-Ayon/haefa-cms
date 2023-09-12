<?php

namespace Modules\Report\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\Patient\Entities\Address;


class Upazilla extends BaseModel
{
    protected $table = 'upazilas';
    public $timestamps = false;
    protected $guarded = [];

    
  
   

  

    public function district()
    {
      return $this->belongsTo(District::class, 'district_id', 'id');
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
