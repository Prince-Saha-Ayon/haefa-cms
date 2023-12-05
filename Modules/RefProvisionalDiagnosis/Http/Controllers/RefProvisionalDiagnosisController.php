<?php

namespace Modules\RefProvisionalDiagnosis\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefProvisionalDiagnosis\Entities\RefProvisionalDiagnosis;
use Modules\RefProvisionalDiagnosis\Http\Requests\RefProvisionalDiagnosisFormRequest;
use Modules\RefProvisionalDiagnosisGroup\Entities\RefProvisionalDiagnosisGroup;

class RefProvisionalDiagnosisController extends BaseController
{
    protected $model;
    public function __construct(RefProvisionalDiagnosis $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        set_time_limit(3600);
        if(permission('refprovisionaldiagnosis-access')){
            $dx_group=RefProvisionalDiagnosisGroup::get();
          
            $this->setPageData('Provisional Diagnosis','Provisional Diagnosis','fas fa-th-list');
            return view('refprovisionaldiagnosis::index',compact('dx_group'));
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        // set_time_limit(3600);
        if(permission('refprovisionaldiagnosis-access')){
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

                    if(permission('refprovisionaldiagnosis-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefProvisionalDiagnosisId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refprovisionaldiagnosis-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refprovisionaldiagnosis-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefProvisionalDiagnosisId . '" data-name="' . $value->RefProvisionalDiagnosisId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refprovisionaldiagnosis-bulk-delete')){
                        $row[] = table_checkbox($value->RefProvisionalDiagnosisId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->ProvisionalDiagnosisCode;
                    $row[] = $value->diagnosisgroup->RefProvisionalDiagnosisGroupCode;
                    $row[] = $value->ProvisionalDiagnosisName;
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
    public function store_or_update_data(RefProvisionalDiagnosisFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refprovisionaldiagnosis-add') || permission('refprovisionaldiagnosis-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefProvisionalDiagnosisId) && !empty($request->RefProvisionalDiagnosisId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefProvisionalDiagnosisId,$collection);
                    $result = $this->model->where('RefProvisionalDiagnosisId', $request->RefProvisionalDiagnosisId)->update($collection->all());
                    $output = $this->store_message($result,$request->RefProvisionalDiagnosisId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefProvisionalDiagnosisId,$collection);
                    //update existing index value
                    $collection['RefProvisionalDiagnosisId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RefProvisionalDiagnosisId);
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
            if (permission('refprovisionaldiagnosis-delete')) {
                $result = $this->model->where('RefProvisionalDiagnosisId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefProvisionalDiagnosisId);
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
                if (permission('refprovisionaldiagnosis-edit')) {
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
        return $this->model->where('RefProvisionalDiagnosisId',$request->RefProvisionalDiagnosisId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refprovisionaldiagnosis-view')){
            if($request->ajax()){
                if (permission('refprovisionaldiagnosis-view')) {
                    $RefProvisionalDiagnosiss= RefProvisionalDiagnosis::where('RefProvisionalDiagnosisId','=',$request->id)->first(); 
                }
            }
            return view('refprovisionaldiagnosis::details',compact('RefProvisionalDiagnosiss'))->render(); 
        
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
            if(permission('refprovisionaldiagnosis-edit')){
               $output = DB::table('RefProvisionalDiagnosis')->where('RefProvisionalDiagnosisId',$request->id)->first();
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
                if(permission('refprovisionaldiagnosis-bulk-delete')){
                    $result = $this->model->whereIn('RefProvisionalDiagnosisId',$request->ids)->delete();
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
