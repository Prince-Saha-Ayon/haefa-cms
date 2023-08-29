<?php

namespace Modules\Gender\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Gender\Entities\Gender;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Gender\Http\Requests\GenderFormRequest;

class GenderController extends BaseController
{
    public function __construct(Gender $model)  
    {
        $this->model = $model;
    }

    public function index()  
    {
        if(permission('gender-access')){
            $this->setPageData('Gender','Gender','fas fa-th-list');
            return view('gender::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('gender-access')){
            if($request->ajax()){
                if (!empty($request->GenderCode)) {
                    $this->model->setGenderCode($request->GenderCode);
                }
                if (!empty($request->Description)) {
                    $this->model->setDescription($request->Description);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('gender-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->GenderId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('gender-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->GenderId . '" data-name="' . $value->Description . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if(permission('gender-bulk-delete')){
                        $row[] = table_checkbox($value->GenderId);
                    }
                    $row[] = $no;
                    $row[] = $value->GenderCode;
                    $row[] = $value->Description;
                    $row[] = permission('gender-edit') ? change_status($value->GenderId,$value->status,$value->GenderCode) : STATUS_LABEL[$value->status];;
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                 $this->model->count_filtered(), $data);
            }else{
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    public function store_or_update_data(GenderFormRequest $request)
    {
      
        if($request->ajax()){
            if(permission('gender-add') || permission('gender-edit')){
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
                $result = $this->model->updateOrCreate(['GenderId'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('gender-edit')){
                $data = $this->model->findOrFail($request->GenderId);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('gender-delete')){
                $result = $this->model->find($request->GenderId)->delete();
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('gender-bulk-delete')){
                $result = $this->model->destroy($request->ids);
                $output = $this->bulk_delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('gender-edit')) {
                $result = $this->model->find($request->GenderId)->update(['status'=>$request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }


}
