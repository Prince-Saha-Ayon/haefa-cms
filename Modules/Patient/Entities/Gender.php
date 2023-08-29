<?php

namespace Modules\Patient\Entities;
use Modules\Patient\Entities\Patient;
use Modules\Base\Entities\BaseModel;

class Gender extends BaseModel
{
    protected $table = 'RefGender';
    public $timestamps = false;

    public function patient()
    {
      return $this->belongsTo(Patient::class, 'GenderId', 'GenderId');
    }
}
