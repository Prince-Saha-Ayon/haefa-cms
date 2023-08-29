<?php

namespace Modules\RefDepartment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\RefDepartment\Http\Requests\RefDepartmentFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefDepartmentController extends BaseController
{
    protected $model;
    public function __construct(RefDepartment $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refdepartment-access')){
            $this->setPageData('RefDepartment','RefDepartment','fas fa-th-list');
            $data['workplaces'] = DB::select("SELECT * FROM WorkPlace");  
            return view('refdepartment::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refdepartment-access')){
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

                    if(permission('refdepartment-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefDepartmentId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refdepartment-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->RefDepartmentId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refdepartment-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefDepartmentId . '" data-name="' . $value->RefDepartmentId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refdepartment-bulk-delete')){
                        $row[] = table_checkbox($value->RefDepartmentId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->DepartmentCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refdepartment-edit') ? change_status($value->RefDepartmentId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
            if (permission('refdepartment-edit')) {
                    $result = $this->update_change_status($request);
            if($result){
                return response()->json(['status'=>'success','message'=>'Status CHanged Successfully']);
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
        return $this->model->where('RefDepartmentId',$request->RefDepartmentId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refdepartment-view')){
            if($request->ajax()){
                if (permission('refdepartment-view')) {
                    $RefDepartment = DB::select("SELECT wp.WorkPlaceName,rd.DepartmentCode,rd.Description 
                    FROM RefDepartment AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    WHERE rd.RefDepartmentId='$request->id'");
                }
            }
            return view('refdepartment::details',compact('RefDepartment'))->render();
        
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
         $data1 = DB::select("SELECT wp.WorkPlaceName,rd.DepartmentCode,rd.Description ,rd.WorkPlaceId,
                    rd.RefDepartmentId FROM RefDepartment AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    WHERE rd.RefDepartmentId='$request->id'"); 
         
         return response()->json(['department'=>$data1]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefDepartmentFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refdepartment-add') || permission('refdepartment-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefDepartmentId) && !empty($request->RefDepartmentId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefDepartmentId,$collection);
                    $result = $this->model->where('RefDepartmentId', $request->RefDepartmentId)->update($collection->all());
                    $output = $this->store_message($result,$request->RefDepartmentId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefDepartmentId,$collection);
                    //update existing index value
                    $collection['RefDepartmentId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RefDepartmentId);
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
            if (permission('refdepartment-delete')) {
                $result = $this->model->where('RefDepartmentId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefDepartmentId);
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
                if(permission('refdepartment-bulk-delete')){
                    $result = $this->model->whereIn('RefDepartmentId',$request->ids)->delete();
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
