<?php

namespace Modules\RefDuration\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefDuration\Entities\RefDuration;
use Modules\RefDuration\Http\Requests\RefDurationFormRequest;

class RefDurationController extends BaseController
{
     protected $model;
    public function __construct(RefDuration $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refduration-access')){
            $this->setPageData('RefDrugGroup','RefDrugGroup','fas fa-th-list');
            return view('refduration::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refduration-access')){
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

                    if(permission('refduration-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->DurationId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refduration-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refduration-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->DurationId . '" data-name="' . $value->DurationId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refduration-bulk-delete')){
                        $row[] = table_checkbox($value->DurationId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->DurationCode;
                    $row[] = $value->DurationInEnglish;
                    $row[] = $value->DurationInBangla;
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
    public function store_or_update_data(RefDurationFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refduration-add') || permission('refduration-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->DurationId) && !empty($request->DurationId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->DurationId,$collection);
                    $result = $this->model->where('DurationId', $request->DurationId)->update($collection->all());
                    $output = $this->store_message($result,$request->DurationId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->DurationId,$collection);
                    //update existing index value
                    $collection['DurationId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->DurationId);
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
            if (permission('refduration-delete')) {
                $result = $this->model->where('DurationId',$request->id)->delete();
                $output = $this->store_message($result,$request->DurationId);
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
                if (permission('refduration-edit')) {
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
        return $this->model->where('DurationId',$request->DurationId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refduration-view')){
            if($request->ajax()){
                if (permission('refduration-view')) {
                    $RefDurations= RefDuration::where('DurationId','=',$request->id)->first(); 
                }
            }
            return view('refduration::details',compact('RefDurations'))->render();
        
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
            if(permission('refduration-edit')){
               $output = DB::table('RefDuration')->where('DurationId',$request->id)->first();
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
                if(permission('refduration-bulk-delete')){
                    $result = $this->model->whereIn('DurationId',$request->ids)->delete();
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

