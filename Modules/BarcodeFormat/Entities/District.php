<?php

namespace Modules\BarcodeFormat\Entities;
use Modules\Base\Entities\BaseModel;

class District extends BaseModel
{
    protected $table = 'districts';
    public $timestamps = false;
    protected $fillable = ['id','division_id','name','bn_name','lat','lon',
        'url'];
}
