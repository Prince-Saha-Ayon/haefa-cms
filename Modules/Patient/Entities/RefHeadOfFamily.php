<?php

namespace Modules\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefHeadOfFamily extends Model
{
    use HasFactory;

    protected $table = 'RefHeadOfFamily';
    public $timestamps = false;
    protected $fillable = ['HeadOfFamilyId', 'HeadOfFamilyCode', 'Description','SortOrder',
        'Status','CreateDate', 'CreateUser','UpdateDate', 'UpdateUser', 'OrgId'];

    protected static function newFactory()
    {
        return \Modules\Patient\Database\factories\RefHeadOfFamilyFactory::new();
    }
}
