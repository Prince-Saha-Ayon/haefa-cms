<?php

namespace Modules\RefVaccineDose\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefVaccineDose\Entities\RefVaccineDose;
use Modules\RefVaccineDose\Http\Requests\RefVaccineDoseFormRequest;

class RefVaccineDoseController extends BaseController
{
     protected $model;
    public function __construct(RefVaccineDose $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refvaccinedose-access')){
            $this->setPageData('Vaccine Dose','Vaccine Dose','fas fa-th-list');
            return view('refvaccinedose::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refvaccinedose-access')){
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

                    if(permission('refvaccinedose-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->VaccineDoseId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refvaccinedose-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->DrugGroupId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refvaccinedose-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->VaccineDoseId . '" data-name="' . $value->VaccineDoseId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refvaccinedose-bulk-delete')){
                        $row[] = table_checkbox($value->VaccineDoseId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->VaccineDoseTitle;
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
    public function store_or_update_data(RefVaccineDoseFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refvaccinedose-add') || permission('refvaccinedose-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->VaccineDoseId) && !empty($request->VaccineDoseId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->VaccineDoseId,$collection);
                    $result = $this->model->where('VaccineDoseId', $request->VaccineDoseId)->update($collection->all());
                    $output = $this->store_message($result,$request->VaccineDoseId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->VaccineDoseId,$collection);
                    //update existing index value
                    $collection['VaccineDoseId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->VaccineDoseId);
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
            if (permission('refvaccinedose-delete')) {
                $result = $this->model->where('VaccineDoseId',$request->id)->delete();
                $output = $this->store_message($result,$request->VaccineDoseId);
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
                if (permission('refvaccinedose-edit')) {
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
        return $this->model->where('VaccineDoseId',$request->VaccineDoseId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refvaccinedose-view')){
            if($request->ajax()){
                if (permission('refvaccinedose-view')) {
                    $RefVaccineDoses= RefVaccineDose::where('VaccineDoseId','=',$request->id)->first(); 
                }
            }
            return view('refvaccinedose::details',compact('RefVaccineDoses'))->render();
        
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
            if(permission('refvaccinedose-edit')){
               $output = DB::table('RefVaccineDose')->where('VaccineDoseId',$request->id)->first();
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
                if(permission('refvaccinedose-bulk-delete')){
                    $result = $this->model->whereIn('VaccineDoseId',$request->ids)->delete();
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