<?php

namespace Modules\Report\Entities;
use Modules\Base\Entities\BaseModel;
use Modules\Patient\Entities\Address;


class ChiefComplain extends BaseModel
{
    protected $table = 'RefChiefComplain';
    public $timestamps = false;
    protected $guarded = [];
    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;
    public $incrementing = false;

    public function setName($name)
    {
        $this->name = $name;
    }
  

  

    // public function prescription()
    // {
    //   return $this->belongsTo(Prescription::class, 'PatientId', 'PatientId');
    // }
    
    
   

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
