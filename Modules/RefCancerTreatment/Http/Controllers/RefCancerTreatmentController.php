<?php

namespace Modules\RefCancerTreatment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefCancerTreatment\Entities\RefCancerTreatment;
use Modules\RefCancerTreatment\Http\Requests\RefCancerTreatmentFormRequest;

class RefCancerTreatmentController extends BaseController
{
     protected $model;
    public function __construct(RefCancerTreatment $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refcancertreatment-access')){
            $this->setPageData('RefCancerTreatment','RefCancerTreatment','fas fa-th-list');
            return view('refcancertreatment::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refcancertreatment-access')){
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

                    if(permission('refcancertreatment-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->CancerTreatmentId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refcancertreatment-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refcancertreatment-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->CancerTreatmentId . '" data-name="' . $value->CancerTreatmentId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refcancertreatment-bulk-delete')){
                        $row[] = table_checkbox($value->CancerTreatmentId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->CancerTreatmentCode;
                    $row[] = $value->Description;
                    // $row[] = permission('RefBloodGroup-edit') ? change_status($value->DrugGroupId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefCancerTreatmentFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refcancertreatment-add') || permission('refcancertreatment-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->CancerTreatmentId) && !empty($request->CancerTreatmentId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->CancerTreatmentId,$collection);
                    $result = $this->model->where('CancerTreatmentId', $request->CancerTreatmentId)->update($collection->all());
                    $output = $this->store_message($result,$request->CancerTreatmentId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->CancerTreatmentId,$collection);
                    //update existing index value
                    $collection['CancerTreatmentId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->CancerTreatmentId);
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
            if (permission('refcancertreatment-delete')) {
                $result = $this->model->where('CancerTreatmentId',$request->id)->delete();
                $output = $this->store_message($result,$request->CancerTreatmentId);
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
                if (permission('refcancertreatment-edit')) {
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
            // return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('CancerTreatmentId',$request->CancerTreatmentId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refcancertreatment-view')){
            if($request->ajax()){
                if (permission('refcancertreatment-view')) {
                    $RefCancerTreatments= RefCancerTreatment::where('CancerTreatmentId','=',$request->id)->first(); 
                }
            }
            return view('refcancertreatment::details',compact('RefCancerTreatments'))->render();
        
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
         if($request->ajax()){
            if(permission('refcancertreatment-edit')){
               $output = DB::table('RefCancerTreatment')->where('CancerTreatmentId',$request->id)->first();
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
            try{
                if(permission('refcancertreatment-bulk-delete')){
                    $result = $this->model->whereIn('CancerTreatmentId',$request->ids)->delete();
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