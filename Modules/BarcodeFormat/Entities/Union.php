<?php

namespace Modules\BarcodeFormat\Entities;
use Modules\Base\Entities\BaseModel;

class Union extends BaseModel
{
    protected $table = 'unions';
    public $timestamps = false;
    protected $fillable = ['id','upazilla_id','name','bn_name','url'];
}
