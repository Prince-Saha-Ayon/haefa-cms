<?php

namespace Modules\ProductionAPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientRegistration extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\ProductionAPI\Database\factories\PatientRegistrationFactory::new();
    }
}
