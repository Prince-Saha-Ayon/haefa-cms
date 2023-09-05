<?php

namespace Modules\RefQuestion\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RefAnswer extends Model
{
    use HasFactory;

    protected $table = 'RefAnswer';
    public $timestamps = false;

    protected $fillable = ['AnswerId','AnswerGroupId','AnswerModuleName','AnswerTitle',
        'Description','ButtonType','SortOrder','Status','CreateDate','CreateUser',
        'UpdateDate','UpdateUser','OrgId'];
}
