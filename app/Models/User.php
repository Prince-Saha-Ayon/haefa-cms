<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Modules\BarcodeFormat\Entities\BarcodeFormat;
use Modules\Employee\Entities\Employee;
use Modules\Organization\Entities\Organization;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id','cc_id','OrgId','EmployeeId','name', 'email', 'password','avatar','mobile_no','status','gender','created_by','modified_by','station'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class,'EmployeeId','EmployeeId');
    }
    public function barcodeFormat()
    {
        return $this->belongsTo(BarcodeFormat::class,'id','cc_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class,'OrgId','OrgId');
    }
}
