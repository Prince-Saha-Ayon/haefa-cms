<?php

namespace Modules\Patient\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MDataPatientIllnessHistory extends Model
{
    use HasFactory;

    protected $table = 'MDataPatientIllnessHistory';
    public $timestamps = false;
    protected $fillable = ['MDPatientIllnessId', 'PatientId', 'CollectionDate','IllnessId','OtherIllness',
        'Status','CreateDate', 'CreateUser','UpdateDate', 'UpdateUser', 'OrgId'];

    public function illness() {
        return $this->belongsTo('Modules\RefIllness\Entities\RefIllness', 'IllnessId', 'IllnessId');
    }

    public function patient() {
        return $this->belongsTo('Modules\Patient\Entities\Patient', 'PatientId', 'PatientId');
    }

    protected static function newFactory()
    {
        return \Modules\Patient\Database\factories\MDataPatientIllnessHistoryFactory::new();
    }
}
