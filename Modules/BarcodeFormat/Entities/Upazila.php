<?php

namespace Modules\BarcodeFormat\Entities;
use Modules\Base\Entities\BaseModel;

class Upazila extends BaseModel
{
    protected $table = 'upazilas';
    public $timestamps = false;
    protected $fillable = ['id','district_id','name','bn_name',
        'url'];
}
