<?php

namespace Modules\Patient\Entities;
use Modules\Patient\Entities\Patient;
use Modules\Base\Entities\BaseModel;

class MaritalStatus extends BaseModel
{
    protected $table = 'RefMaritalStatus';
    public $timestamps = false;

    public function patient()
    {
      return $this->belongsTo(Patient::class, 'GenderId', 'GenderId');
    }
}