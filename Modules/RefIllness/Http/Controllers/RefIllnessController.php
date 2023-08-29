<?php

namespace Modules\Refillness\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\Refillness\Entities\Refillness;
use Modules\Refillness\Http\Requests\RefillnessFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefillnessController extends BaseController
{
    
    protected $model;
    public function __construct(Refillness $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refillness-access')){
            $this->setPageData('Refillness','Refillness','fas fa-th-list');
            return view('refillness::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refillness-access')){
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

                    if(permission('refillness-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->IllnessId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refillness-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->IllnessId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refillness-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->IllnessId . '" data-name="' . $value->IllnessId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refillness-bulk-delete')){
                        $row[] = table_checkbox($value->IllnessId);
                    }
                   
                    $row[] = $no;
                    $row[] = $value->IllnessCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refillness-edit') ? change_status($value->IllnessId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefillnessFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refillness-add') || permission('refillness-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->IllnessId) && !empty($request->IllnessId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->IllnessId,$collection);
                    $result = $this->model->where('IllnessId', $request->IllnessId)->update($collection->all());
                    $output = $this->store_message($result,$request->IllnessId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->IllnessId,$collection);
                    //update existing index value
                    $collection['IllnessId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->IllnessId);
                    return response()->json($output);
                }

                }catch(\Exception $e){
                    // return response()->json(['status'=>'error','message'=>$e->getMessage()]);
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
            if (permission('refillness-delete')) {
                $result = $this->model->where('IllnessId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefillnessId);
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
            if (permission('refillness-edit')) {
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
            return response()->json(['status'=>'error','message'=>'Something went wrong!']);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('IllnessId',$request->IllnessId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refillness-view')){
            if($request->ajax()){
                if (permission('refillness-view')) {
                    $Refillnesss= Refillness::where('IllnessId','=',$request->id)->first(); 
                }
            }
            return view('refillness::details',compact('Refillnesss'))->render();
        
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
        return $data = DB::table('Refillness')->where('IllnessId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refillness-bulk-delete')){
                    $result = $this->model->whereIn('IllnessId',$request->ids)->delete();
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
