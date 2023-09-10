<?php

namespace Modules\Patient\Entities;
use Modules\Patient\Entities\Address;
use Modules\Base\Entities\BaseModel;
use Modules\Upazila\Entities\Upazila;

class District extends BaseModel
{
    protected $table = 'districts';
    public $timestamps = false;
    protected $fillable = ['id','division_id','name','bn_name','lat','lon','url'];

    public function get_upazilla(){
        return $this->hasMany(Upazila::class,'id','district_id');
    }
}
