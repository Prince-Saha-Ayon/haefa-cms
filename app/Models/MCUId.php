<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MCUId extends Model
{
    protected $fillable = ['MDLCCID','MDStartD','MDUID','MDDate','Status','CreateDate','CreateUser','UpdateDate','UpdateUser'];
    protected $table = 'MDataPatientCC';
    public $timestamps = false;

}