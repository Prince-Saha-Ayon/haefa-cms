<?php

namespace Modules\RefChiefComplain\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefChiefComplain\Entities\RefChiefComplain;
use Modules\RefChiefComplain\Http\Requests\RefChiefComplainFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefChiefComplainController extends BaseController
{
    protected $model;
    public function __construct(RefChiefComplain $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refchiefcomplain-access')){
            $this->setPageData('Refchiefcomplain','Refchiefcomplain','fas fa-th-list');
            return view('refchiefcomplain::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refchiefcomplain-access')){
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

                    if(permission('refchiefcomplain-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->CCId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refchiefcomplain-view')){
                        // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->CCId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refchiefcomplain-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->CCId . '" data-name="' . $value->CCId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refchiefcomplain-bulk-delete')){
                        $row[] = table_checkbox($value->CCId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->CCCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refchiefcomplain-edit') ? change_status($value->CCId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
            if (permission('user-edit')) {
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
        return $this->model->where('CCId',$request->CCId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refchiefcomplain-view')){
            if($request->ajax()){
                if (permission('refchiefcomplain-view')) {
                    $RefChiefComplaints= RefChiefComplain::where('CCId','=',$request->id)->first(); 
                }
            }
            return view('refchiefcomplain::details',compact('RefChiefComplaints'))->render();
        
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
        return $data = DB::table('RefChiefComplain')->where('CCId',$request->id)->first();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefChiefComplainFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refchiefcomplain-add') || permission('refchiefcomplain-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->CCId) && !empty($request->CCId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->CCId,$collection);
                    $result = $this->model->where('CCId', $request->CCId)->update($collection->all());
                    $output = $this->store_message($result,$request->CCId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->CCId,$collection);
                    //update existing index value
                    $collection['CCId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->CCId);
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
            if (permission('refchiefcomplain-delete')) {
                $result = $this->model->where('CCId',$request->id)->delete();
                $output = $this->store_message($result,$request->CCId);
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
                if(permission('refchiefcomplain-bulk-delete')){
                    $result = $this->model->whereIn('CCId',$request->ids)->delete();
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
