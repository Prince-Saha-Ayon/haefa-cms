<?php

namespace Modules\RefQuestion\Entities;

use Modules\Base\Entities\BaseModel;

class RefQuestion extends BaseModel
{
    protected $table = 'RefQuestion';
    protected $primaryKey = 'QuestionId';
    public $timestamps = false;

    protected $fillable = ['QuestionId','QuestionTypeId','QuestionModuleName','QuestionGroupId',
    'QuestionTitle','Description','SortOrder','Status','CreateDate','CreateUser',
    'UpdateDate','UpdateUser','OrgId'];

    protected $order = ['CreateDate'=>'desc'];
    
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    private function get_datatable_query()
    {
        if(permission('refquestion-bulk-delete')){
            //datatable display data from the below fields
            $this->column_order = [null,'QuestionModuleName','QuestionTitle','Description',
            'Status',null];
        }else{
            $this->column_order = ['QuestionModuleName','QuestionTitle','Description','Status',null];
        }

        $query = self::toBase();

        /*****************
            * *Search Data **
            ******************/
        //    
        if (!empty($this->name)) {
            $query->where('QuestionModuleName','like', '%'.$this->name.'%');
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->get()->count();
    }

    public function count_all()
    {
        return self::toBase()->get()->count();
    }
}
