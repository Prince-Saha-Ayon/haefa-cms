<?php

namespace Modules\Upazila\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\Patient\Entities\District;

class Upazila extends BaseModel
{
    protected $table = 'upazilas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id','district_id','name','bn_name',
    'url'];

    protected $order = ['name'=>'desc'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('union-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'name',null];
        }else{
            $this->column_order = ['name',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //
        if (!empty($this->name)) {
            $query->where('name','like', '%'.$this->name.'%');
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

    public function get_district(){
        return $this->belongsTo(District::class,'district_id','id');
    }

}
