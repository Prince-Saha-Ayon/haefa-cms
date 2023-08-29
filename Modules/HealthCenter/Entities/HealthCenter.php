<?php

namespace Modules\HealthCenter\Entities;

use Modules\Base\Entities\BaseModel;

class HealthCenter extends BaseModel
{
    protected $table = 'HealthCenter';
    protected $primaryKey = 'HealthCenterId';
    public $timestamps = false;

    protected $fillable = ['HealthCenterId','HealthCenterCode','HealthCenterName','HealthCenterContactNumber',
    'ContactPersonName','ContactPersonMobile','ContactPersonEmail','IsProvideAmbulance','AmbulanceFee',
    'NearestLandmark','DistanceFrom','IsProvideOTFacility','OTFee','IsProvidePostOperativeFacility',
    'PostOperativeFee','IsProvideICUFacility','ICUFee','IsProvideHDUFacility','HDUFee','IsProvideCCUFacility',
    'CCUFee','IsProvideNICUFacility','NICUFee','IsProvideBurnTreatment','BurnTreatmentFee',
    'IsProvideDiscountWheelsReferredPatient','DiscountPercentageWheelsReferredPatient','HealthCenterType',
    'Status','CreateUser','CreateDate','UpdateUser','UpdateDate','OrgId','Latitude','Longitude'];

    protected $order = ['CreateDate'=>'desc'];

    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    private function get_datatable_query()
    {
        if(permission('union-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'HealthCenterCode','HealthCenterName','HealthCenterContactNumber',null];
        }else{
            $this->column_order = ['HealthCenterCode','HealthCenterName','HealthCenterContactNumber',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //
        if (!empty($this->name)) {
            $query->where('HealthCenterCode','like', '%'.$this->name.'%');
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
