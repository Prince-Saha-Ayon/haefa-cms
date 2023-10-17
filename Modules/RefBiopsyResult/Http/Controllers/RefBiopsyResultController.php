<?php

namespace Modules\RefBiopsyResult\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefBiopsyResult\Entities\RefBiopsyResult;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;
use Modules\RefBiopsyResult\Http\Requests\RefBiopsyResultFormRequest;

class RefBiopsyResultController extends BaseController
{
     protected $model;
    public function __construct(RefBiopsyResult $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refbiopsyresult-access')){
            $this->setPageData('RefBiopsyResult','RefBiopsyResult','fas fa-th-list');
            return view('refbiopsyresult::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refbiopsyresult-access')){
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

                    if(permission('refbiopsyresult-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->BiopsyResultId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refbiopsyresult-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->BiopsyResultId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refbiopsyresult-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->BiopsyResultId . '" data-name="' . $value->BiopsyResultId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refbiopsyresult-bulk-delete')){
                        $row[] = table_checkbox($value->BiopsyResultId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->BiopsyResultCode;
                    $row[] = $value->Description;
                    // $row[] = permission('RefBloodGroup-edit') ? change_status($value->BiopsyResultId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefBiopsyResultFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refbiopsyresult-add') || permission('refbiopsyresult-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->BiopsyResultId) && !empty($request->BiopsyResultId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->BiopsyResultId,$collection);
                    $result = $this->model->where('BiopsyResultId', $request->BiopsyResultId)->update($collection->all());
                    $output = $this->store_message($result,$request->BiopsyResultId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->BiopsyResultId,$collection);
                    //update existing index value
                    $collection['BiopsyResultId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->BiopsyResultId);
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
            if (permission('refbiopsyresult-delete')) {
                $result = $this->model->where('BiopsyResultId',$request->id)->delete();
                $output = $this->store_message($result,$request->BiopsyResultId);
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
                if (permission('refbiopsyresult-edit')) {
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
        return $this->model->where('BiopsyResultId',$request->BiopsyResultId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refbiopsyresult-view')){
            if($request->ajax()){
                if (permission('refbiopsyresult-view')) {
                    $RefBiopsyResults= RefBiopsyResult::where('BiopsyResultId','=',$request->id)->first(); 
                }
            }
            return view('RefBloodGroup::details',compact('RefBiopsyResults'))->render();
        
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
        return $data = DB::table('RefBiopsyResult')->where('BiopsyResultId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refbiopsyresult-bulk-delete')){
                    $result = $this->model->whereIn('BiopsyResultId',$request->ids)->delete();
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
