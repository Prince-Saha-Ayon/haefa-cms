<?php

namespace Modules\RefQuestion\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefQuestion\Entities\RefQuestion;
use Modules\RefQuestion\Http\Requests\RefQuestionFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefQuestionController extends BaseController
{
    protected $model;
    public function __construct(RefQuestion $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refquestion-access')){
            $this->setPageData('RefQuestion','RefQuestion','fas fa-th-list');
            $data['types'] = DB::table('RefQuestionType')->get();
            return view('refquestion::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refquestion-access')){
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

                    if(permission('refquestion-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->QuestionId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refquestion-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->QuestionId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refquestion-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->QuestionId . '" data-name="' . $value->QuestionId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refquestion-bulk-delete')){
                        $row[] = table_checkbox($value->QuestionId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->QuestionModuleName;
                    $row[] = $value->QuestionTitle;
                    $row[] = $value->Description;
                    // $row[] = permission('refquestion-edit') ? change_status($value->QuestionId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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

    public function change_status(Request $request)
    {
        try{
            if($request->ajax()){
            if (permission('refquestion-edit')) {
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
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('QuestionId',$request->QuestionId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        return $data = DB::table('RefQuestion')->where('QuestionId',$request->id)->first();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefQuestionFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refquestion-add') || permission('refquestion-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->QuestionId) && !empty($request->QuestionId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->QuestionId,$collection);
                    $result = $this->model->where('QuestionId', $request->QuestionId)->update($collection->all());
                    $output = $this->store_message($result,$request->QuestionId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->QuestionId,$collection);
                    //update existing index value
                    $collection['QuestionId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->QuestionId);
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
            if (permission('refquestion-delete')) {
                $result = $this->model->where('QuestionId',$request->id)->delete();
                $output = $this->store_message($result,$request->QuestionId);
                return response()->json($output);
            }else{
                return response()->json($this->access_blocked());
            }
        }else{
           return response()->json(['status'=>'error','message'=>'Something went wrong !']);
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refquestion-bulk-delete')){
                    $result = $this->model->whereIn('QuestionId',$request->ids)->delete();
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


