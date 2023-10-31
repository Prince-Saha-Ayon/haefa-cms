<?php

namespace Modules\RefProvisionalDiagnosisGroup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefProvisionalDiagnosisGroup\Entities\RefProvisionalDiagnosisGroup;
use Modules\RefProvisionalDiagnosisGroup\Http\Requests\RefProvisionalDiagnosisGroupFormRequest;

class RefProvisionalDiagnosisGroupController extends BaseController
{
     protected $model;
    public function __construct(RefProvisionalDiagnosisGroup $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refprovisionaldiagnosisgroup-access')){
            $this->setPageData('ProvisionalDiagnosis Group','ProvisionalDiagnosis Group','fas fa-th-list');
            return view('refprovisionaldiagnosisgroup::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refprovisionaldiagnosisgroup-access')){
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

                    if(permission('refprovisionaldiagnosisgroup-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefProvisionalDiagnosisGroupId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refprovisionaldiagnosisgroup-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refprovisionaldiagnosisgroup-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefProvisionalDiagnosisGroupId . '" data-name="' . $value->RefProvisionalDiagnosisGroupId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refprovisionaldiagnosisgroup-bulk-delete')){
                        $row[] = table_checkbox($value->RefProvisionalDiagnosisGroupId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->RefProvisionalDiagnosisGroupCode;
                    $row[] = $value->Category;
                    $row[] = $value->CommonTerm;
                
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
    public function store_or_update_data(RefProvisionalDiagnosisGroupFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refprovisionaldiagnosisgroup-add') || permission('refprovisionaldiagnosisgroup-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefProvisionalDiagnosisGroupId) && !empty($request->RefProvisionalDiagnosisGroupId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefProvisionalDiagnosisGroupId,$collection);
                    $result = $this->model->where('RefProvisionalDiagnosisGroupId', $request->RefProvisionalDiagnosisGroupId)->update($collection->all());
                    $output = $this->store_message($result,$request->RefProvisionalDiagnosisGroupId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefProvisionalDiagnosisGroupId,$collection);
                    //update existing index value
                    $collection['RefProvisionalDiagnosisGroupId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RefProvisionalDiagnosisGroupId);
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
            if (permission('refprovisionaldiagnosisgroup-delete')) {
                $result = $this->model->where('RefProvisionalDiagnosisGroupId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefProvisionalDiagnosisGroupId);
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
                if (permission('refprovisionaldiagnosisgroup-edit')) {
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
        return $this->model->where('RefProvisionalDiagnosisGroupId',$request->RefProvisionalDiagnosisGroupId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refprovisionaldiagnosisgroup-view')){
            if($request->ajax()){
                if (permission('refprovisionaldiagnosisgroup-view')) {
                    $RefProvisionalDiagnosisGroups= RefProvisionalDiagnosisGroup::where('RefProvisionalDiagnosisGroupId','=',$request->id)->first(); 
                }
            }
            return view('refprovisionaldiagnosisgroup::details',compact('RefProvisionalDiagnosisGroups'))->render();
        
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
            if(permission('refprovisionaldiagnosisgroup-edit')){
               $output = DB::table('RefProvisionalDiagnosisGroup')->where('RefProvisionalDiagnosisGroupId',$request->id)->first();
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
                if(permission('refprovisionaldiagnosisgroup-bulk-delete')){
                    $result = $this->model->whereIn('RefProvisionalDiagnosisGroupId',$request->ids)->delete();
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