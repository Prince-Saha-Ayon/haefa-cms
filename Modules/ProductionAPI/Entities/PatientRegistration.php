<?php

namespace Modules\ProductionAPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\BaseModel;

class PatientRegistration extends BaseModel
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ProductionAPI\Database\factories\PatientRegistrationFactory::new();
    }
}
