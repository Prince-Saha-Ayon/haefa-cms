<?php

namespace Modules\Report\Entities;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MDataPatientObsGynaeView extends BaseModel
{
    use HasFactory;

    protected $table = 'MDataPatientObsGynaeView';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        
    }
}