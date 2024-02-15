<?php

namespace Modules\ProductionAPI\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Entities\BaseModel;

class ApiConditionTrigView extends BaseModel
{
    protected $table = 'ApiConditionTrigView';

    protected $guarded = [];
}
