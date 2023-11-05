<?php

namespace Modules\Report\Entities;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlucoseHostMale extends BaseModel
{
    use HasFactory;

    protected $table = 'GlucoseHostMale';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        
    }
}