<?php

namespace Modules\RefLabInvestigationGroup\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefLabInvestigationGroup\Entities\RefLabInvestigationGroup;
use Modules\RefLabInvestigationGroup\Http\Requests\RefLabInvestigationGroupFormRequest;

class RefLabInvestigationGroupController extends BaseController
{
     protected $model;
    public function __construct(RefLabInvestigationGroup $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('reflabinvestigationgroup-access')){
            $this->setPageData('RefLabInvestigationGroup','RefLabInvestigationGroup','fas fa-th-list');
            return view('reflabinvestigationgroup::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('reflabinvestigationgroup-access')){
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

                    if(permission('reflabinvestigationgroup-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefLabInvestigationGroupId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('reflabinvestigationgroup-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('reflabinvestigationgroup-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefLabInvestigationGroupId . '" data-name="' . $value->RefLabInvestigationGroupId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('reflabinvestigationgroup-bulk-delete')){
                        $row[] = table_checkbox($value->RefLabInvestigationGroupId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->RefLabInvestigationGroupCode;
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
    public function store_or_update_data(RefLabInvestigationGroupFormRequest $request)
    {
        if($request->ajax()){
            if(permission('reflabinvestigationgroup-add') || permission('reflabinvestigationgroup-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefLabInvestigationGroupId) && !empty($request->RefLabInvestigationGroupId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefLabInvestigationGroupId,$collection);
                    $result = $this->model->where('RefLabInvestigationGroupId', $request->RefLabInvestigationGroupId)->update($collection->all());
                    $output = $this->store_message($result,$request->RefLabInvestigationGroupId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefLabInvestigationGroupId,$collection);
                    //update existing index value
                    $collection['RefLabInvestigationGroupId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RefLabInvestigationGroupId);
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
            if (permission('reflabinvestigationgroup-delete')) {
                $result = $this->model->where('RefLabInvestigationGroupId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefLabInvestigationGroupId);
                return response()->json($output);
            }else{
                return response()->json($this->access_blocked());
            }
        }else{
           return response()->json(['status'=>'error','message'=>'Something went wrong !']);
        }
    }

  

     public function change_status(Request $request)
    {
        try{
            if($request->ajax()){
                if (permission('reflabinvestigationgroup-edit')) {
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
        return $this->model->where('RefLabInvestigationGroupId',$request->RefLabInvestigationGroupId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('reflabinvestigationgroup-view')){
            if($request->ajax()){
                if (permission('reflabinvestigationgroup-view')) {
                    $RefLabInvestigationGroups= RefLabInvestigationGroup::where('RefLabInvestigationGroupId','=',$request->id)->first(); 
                }
            }
            return view('reflabinvestigationgroup::details',compact('RefRefLabInvestigationGroups'))->render();
        
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
            if(permission('reflabinvestigationgroup-edit')){
               $output = DB::table('RefLabInvestigationGroup')->where('RefLabInvestigationGroupId',$request->id)->first();
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
                if(permission('reflabinvestigationgroup-bulk-delete')){
                    $result = $this->model->whereIn('RefLabInvestigationGroupId',$request->ids)->delete();
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
