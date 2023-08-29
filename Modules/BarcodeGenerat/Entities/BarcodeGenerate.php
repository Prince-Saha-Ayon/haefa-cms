<?php

namespace Modules\BarcodeGenerat\Entities;

use Modules\Base\Entities\BaseModel;
use Modules\BarcodeFormat\Entities\District;
use Modules\BarcodeFormat\Entities\Upazila;
use Modules\BarcodeFormat\Entities\Union;
use Modules\BarcodeFormat\Entities\HealthCenter;

class BarcodeGenerate extends BaseModel
{

    protected $fillable = ['mdata_barcode_prefix','mdata_barcode_number','mdata_barcode_prefix_number','mdata_barcode_generate','mdata_barcode_status','status','created_by','updated_by'];

    protected $table = 'mdatacc_barcodes';

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
    
    protected $name;

    public $timestamps = true;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('bformat-bulk-delete')){
            $this->column_order = [null,'id','mdata_barcode_prefix','mdata_barcode_number','mdata_barcode_prefix_number','mdata_barcode_generate','mdata_barcode_status','status',null];
        }else{
            $this->column_order = ['id','mdata_barcode_prefix','mdata_barcode_number','mdata_barcode_prefix_number','mdata_barcode_generate','mdata_barcode_status','status',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->name)) {
            $query->where('mdata_barcode_prefix', 'like', '%' . $this->name . '%');
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
