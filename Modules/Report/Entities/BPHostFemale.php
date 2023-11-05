<?php

namespace Modules\Report\Entities;
use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BPHostFemale extends BaseModel
{
    use HasFactory;

    protected $table = 'BPHostFemale';

    protected $fillable = [];
    
    protected static function newFactory()
    {
        
    }
}