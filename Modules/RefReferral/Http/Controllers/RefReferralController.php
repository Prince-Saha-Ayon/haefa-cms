<?php

namespace Modules\RefReferral\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefReferral\Entities\RefReferral;
use Modules\RefReferral\Http\Requests\RefReferralFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefReferralController extends BaseController
{
    
    protected $model;
    public function __construct(RefReferral $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refreferral-access')){
            $this->setPageData('RefReferral','RefReferral','fas fa-th-list');
            return view('refreferral::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refreferral-access')){
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

                    if(permission('refreferral-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refreferral-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->RId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refreferral-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RId . '" data-name="' . $value->RId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refreferral-bulk-delete')){
                        $row[] = table_checkbox($value->RId);
                    }
                   
                    $row[] = $no;
                    $row[] = $value->RCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refreferral-edit') ? change_status($value->RId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefReferralFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refreferral-add') || permission('refreferral-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RId) && !empty($request->RId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RId,$collection);
                    $result = $this->model->where('RId', $request->RId)->update($collection->all());
                    $output = $this->store_message($result,$request->RId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RId,$collection);
                    //update existing index value
                    $collection['RId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RId);
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
            if (permission('refreferral-delete')) {
                $result = $this->model->where('RId',$request->id)->delete();
                $output = $this->store_message($result,$request->RId);
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
            if (permission('refreferral-edit')) {
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
        return $this->model->where('RId',$request->RId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        return $data = DB::table('RefReferral')->where('RId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refreferral-bulk-delete')){
                    $result = $this->model->whereIn('RId',$request->ids)->delete();
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
