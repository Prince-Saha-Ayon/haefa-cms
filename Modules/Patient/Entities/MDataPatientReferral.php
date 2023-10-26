<?php

namespace Modules\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MDataPatientReferral extends Model
{
    use HasFactory;

    protected $table = 'MDataPatientReferral';
    public $timestamps = false;
    protected $fillable = ['MDPatientReferralId', 'PatientId','RId','HealthCenterId','CollectionDate',
        'Status','CreateDate', 'CreateUser','UpdateDate', 'UpdateUser', 'OrgId'];

    protected static function newFactory()
    {
        return \Modules\Patient\Database\factories\MDataPatientReferralFactory::new();
    }
}
