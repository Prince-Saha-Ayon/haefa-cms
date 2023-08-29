<?php

namespace Modules\RefDesignation\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefDesignation\Entities\RefDesignation;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefDesignation\Http\Requests\RefDesignationFormRequest;
use Illuminate\Support\Str;
use DB;

class RefDesignationController extends BaseController
{
    protected $model;
    public function __construct(RefDesignation $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refdesignation-access')){
            $this->setPageData('Refdesignation','Refdesignation','fas fa-th-list');
            $data['workplaces'] = DB::select("SELECT * FROM WorkPlace");  
            $data['departments'] = DB::select("SELECT * FROM RefDepartment");
            return view('refdesignation::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refdesignation-access')){
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

                    if(permission('refdesignation-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefDesignationId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refdesignation-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->RefDesignationId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refdesignation-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefDesignationId . '" data-name="' . $value->RefDesignationId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refdesignation-bulk-delete')){
                        $row[] = table_checkbox($value->RefDesignationId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->DesignationTitle;
                    $row[] = $value->Description;
                    // $row[] = permission('refdesignation-edit') ? change_status($value->RefDesignationId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
            if (permission('refdesignation-edit')) {
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
        return $this->model->where('RefDesignationId',$request->RefDesignationId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refdesignation-view')){
            if($request->ajax()){
                if (permission('refdesignation-view')) {
                    $RefDesignation = DB::select("SELECT  rd.DesignationTitle,rd.Description,
                    wp.WorkPlaceName,rfd.DepartmentCode
                    FROM RefDesignation AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.RefDesignationId='$request->id'");
                }
            }
            return view('refdesignation::details',compact('RefDesignation'))->render();
        
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
         $data1 = DB::select("SELECT  rd.DesignationTitle,rd.Description,rd.RefDepartmentId,
                    wp.WorkPlaceName,rfd.DepartmentCode,rd.RefDesignationId,rd.WorkPlaceId
                    FROM RefDesignation AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId 
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.RefDesignationId='$request->id'");

         $data2 = DB::select("SELECT * FROM WorkPlace");  
         $data3 = DB::select("SELECT * FROM RefDepartment");  
         
         return response()->json(['designation'=>$data1,'workplaces'=>$data2,'departments'=> $data3]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefDesignationFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refdesignation-add') || permission('refdesignation-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefDesignationId) && !empty($request->RefDesignationId)){
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->RefDesignationId,$collection);
                        $result = $this->model->where('RefDesignationId', $request->RefDesignationId)
                            ->update($collection->all());
                        $output = $this->store_message($result,$request->RefDesignationId);
                        return response()->json($output);
                    }
                    else{
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->RefDesignationId,$collection);
                        //update existing index value
                        $collection['RefDesignationId'] = Str::uuid();
                        $result = $this->model->create($collection->all());
                        $output = $this->store_message($result,$request->RefDesignationId);
                        return response()->json($output);
                    }

                }catch(\Exception $e){
                    return response()->json(['status'=>'error','message'=>'Something went wrong !']);
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
            if (permission('refdesignation-delete')) {
                $result = $this->model->where('RefDesignationId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefDesignationId);
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
                if(permission('refdesignation-bulk-delete')){
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
