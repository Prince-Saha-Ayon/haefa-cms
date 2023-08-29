<?php

namespace Modules\RefQuestionType\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefQuestionType\Entities\RefQuestionType;
use Modules\RefQuestionType\Http\Requests\RefQuestionTypeFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefQuestionTypeController extends BaseController
{
    
    protected $model;
    public function __construct(RefQuestionType $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refquestiontype-access')){
            $this->setPageData('RefQuestionType','RefQuestionType','fas fa-th-list');
            return view('refquestiontype::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refquestiontype-access')){
            if($request->ajax()){
                
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }

                $this->set_datatable_default_property($request);
                $list = $this->model->getDatatableList();

                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';

                    if(permission('refquestiontype-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->QuestionTypeId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refquestiontype-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->QuestionTypeId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refquestiontype-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->QuestionTypeId . '" data-name="' . $value->QuestionTypeId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refquestiontype-bulk-delete')){
                        $row[] = table_checkbox($value->QuestionTypeId);
                    }
                   
                    $row[] = $no;
                    $row[] = $value->QuestionTypeCode;
                    $row[] = $value->QuestionTypeTitle;
                    // $row[] = permission('refquestiontype-edit') ? change_status($value->QuestionTypeId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefQuestionTypeFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refquestiontype-add') || permission('refquestiontype-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->QuestionTypeId) && !empty($request->QuestionTypeId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->QuestionTypeId,$collection);
                    $result = $this->model->where('QuestionTypeId', $request->QuestionTypeId)->update($collection->all());
                    $output = $this->store_message($result,$request->QuestionTypeId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->QuestionTypeId,$collection);
                    //update existing index value
                    $collection['QuestionTypeId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->QuestionTypeId);
                    return response()->json($output);
                }

                }catch(\Exception $e){
                    return response()->json(['status'=>'error','message'=>$e->getMessage()]);
                    // return response()->json(['status'=>'error','message'=>'Something went wrong !']);
                }
                
            }else{
                $output = $this->access_blocked();
                return response()->json($output);
            }
            
        }else{
           return response()->json($this->access_blocked());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request)
    {
        if($request->ajax()){
            if (permission('refquestiontype-delete')) {
                $result = $this->model->where('QuestionTypeId',$request->id)->delete();
                $output = $this->store_message($result,$request->QuestionTypeId);
                return response()->json($output);
            }else{
                return response()->json($this->access_blocked());
            }
        }else{
           return response()->json(['status'=>'error','message'=>'Something went wrong !']);
        }
    }

    /**
     * Status update
     * @return success or fail message
     */

     public function change_status(Request $request)
    {
        try{
            if($request->ajax()){
            if (permission('refquestiontype-edit')) {
                    $result = $this->update_change_status($request);
            if($result){
                return response()->json(['status'=>'success','message'=>'Status Changed Successfully']);
            }else{
                return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            }
            }else{
                $output = $this->access_blocked();
                return response()->json($output);
            }
            }else{
                return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            }
        }catch(\Exception $e){
            return response()->json(['status'=>'error','message'=>'Something went wrong!']);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('QuestionTypeId',$request->QuestionTypeId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        return $data = DB::table('RefQuestionType')->where('QuestionTypeId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refquestiontype-bulk-delete')){
                    $result = $this->model->whereIn('QuestionTypeId',$request->ids)->delete();
                    $output = $this->bulk_delete_message($result);
                }else{
                    $output = $this->access_blocked();
                }
                return response()->json($output);
            }
            catch(\Exception $e){
                return response()->json(['status'=>'error','message'=>'Something went wrong !']);
            }
        }else{
            return response()->json($this->access_blocked());
        }
    }

}
