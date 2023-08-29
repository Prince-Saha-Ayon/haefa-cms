<?php

namespace Modules\Gender\Entities;

use Modules\Base\Entities\BaseModel;

class Gender extends BaseModel
{
    protected $fillable = ['GenderCode','Description','CreateDate','UpdateDate'];
    
    protected $GenderCode;
    protected $Description;

    public function setGenderCode($GenderCode)
    {
        $this->GenderCode = $GenderCode;
    }
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    private function get_datatable_query()
    {
        if(permission('gender-bulk-delete')){
            $this->column_order = [null,'GenderId','GenderCode','Description',null];
        }else{
            $this->column_order = ['GenderId','GenderCode','Description',null];
        }

        $query = self::toBase();

        /*****************
         * *Search Data **
         ******************/
        if (!empty($this->GenderCode)) {
            $query->where('GenderCode', 'like', '%' . $this->GenderCode . '%');
        }
        if (!empty($this->Description)) {
            $query->where('Description', 'like', '%' . $this->Description . '%');
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
