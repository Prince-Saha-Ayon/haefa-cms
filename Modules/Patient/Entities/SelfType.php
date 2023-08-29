<?php

namespace Modules\Patient\Entities;
use Modules\Patient\Entities\Patient;
use Modules\Base\Entities\BaseModel;

class SelfType extends BaseModel
{
    protected $table = 'RefHeadOfFamily ';
    public $timestamps = false;

    public function patient()
    {
      return $this->belongsTo(Patient::class, 'HeadOfFamilyId', 'IdOwner');
    }
}