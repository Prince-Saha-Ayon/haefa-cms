<?php

namespace Modules\Patient\Entities;
use Modules\Patient\Entities\Patient;
use Modules\Base\Entities\BaseModel;

class Address extends BaseModel
{
    protected $table = 'Address';
    public $timestamps = false;

    public function patient()
    {
      return $this->belongsTo(Patient::class,'PatientId', 'PatientId');
    }
}