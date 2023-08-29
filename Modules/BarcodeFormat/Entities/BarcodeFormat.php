<?php

namespace Modules\BarcodeFormat\Entities;
use Modules\BarcodeFormat\Entities\District;
use Modules\BarcodeFormat\Entities\Upazila;
use Modules\BarcodeFormat\Entities\Union;
use Modules\BarcodeFormat\Entities\HealthCenter;

use Modules\Base\Entities\BaseModel;

class BarcodeFormat extends BaseModel
{
    protected $fillable = ['barcode_district','barcode_upazila','barcode_union','barcode_community_clinic','barcode_prefix','barcode_number','status','created_by','updated_by'];
    protected $table = 'barcode_formats';
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function district()
    {
        return $this->belongsTo(District::class,'barcode_district','Id');
    }
    public function upazila()
    {
        return $this->belongsTo(Upazila::class,'barcode_upazila','Id');
    }
    public function union()
    {
        return $this->belongsTo(Union::class,'barcode_union','Id');
    }
    public function healthCenter()
    {
        return $this->belongsTo(HealthCenter::class,'barcode_community_clinic','HealthCenterId');
    }

    private function get_datatable_query()
    {
        if(permission('bformat-bulk-delete')){
            $this->column_order = [null,'id','barcode_district','barcode_upazila','barcode_union','barcode_community_clinic','barcode_prefix','barcode_number','status',null];
        }else{
            $this->column_order = ['id','barcode_district','barcode_upazila','barcode_union','barcode_community_clinic','barcode_prefix','barcode_number','status',null];
        }

        

        $query = self::with('district');

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
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
