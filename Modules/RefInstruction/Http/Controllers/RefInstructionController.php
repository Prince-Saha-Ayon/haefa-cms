<?php

namespace Modules\RefInstruction\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefInstruction\Entities\RefInstruction;
use Modules\RefInstruction\Http\Requests\RefInstructionFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefInstructionController extends BaseController
{
    protected $model;
    public function __construct(RefInstruction $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refinstruction-access')){
            $this->setPageData('Refinstruction','Refinstruction','fas fa-th-list');
            return view('refinstruction::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refinstruction-access')){
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

                    if(permission('refinstruction-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->RefInstructionId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refinstruction-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->RefInstructionId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refinstruction-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->RefInstructionId . '" data-name="' . $value->RefInstructionId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refinstruction-bulk-delete')){
                        $row[] = table_checkbox($value->RefInstructionId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->InstructionCode;
                    $row[] = $value->InstructionInEnglish;
                    $row[] = $value->InstructionInBangla;
                    $row[] = $value->Description;
                    // $row[] = permission('refinstruction-edit') ? change_status($value->RefInstructionId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
        return $this->model->where('RefInstructionId',$request->RefInstructionId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refinstruction-view')){
            if($request->ajax()){
                if (permission('refinstruction-view')) {
                    $refinstruction= RefInstruction::where('RefInstructionId','=',$request->id)->first(); 
                }
            }
            return view('refinstruction::details',compact('refinstruction'))->render();
        
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
        return $data = DB::table('RefInstruction')->where('RefInstructionId',$request->id)->first();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefInstructionFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refinstruction-add') || permission('refinstruction-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->RefInstructionId) && !empty($request->RefInstructionId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefInstructionId,$collection);
                    $result = $this->model->where('RefInstructionId', $request->RefInstructionId)->update($collection->all());
                    $output = $this->store_message($result,$request->RefInstructionId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->RefInstructionId,$collection);
                    //update existing index value
                    $collection['RefInstructionId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->RefInstructionId);
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
            if (permission('refinstruction-delete')) {
                $result = $this->model->where('RefInstructionId',$request->id)->delete();
                $output = $this->store_message($result,$request->RefInstructionId);
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
                if(permission('refinstruction-bulk-delete')){
                    $result = $this->model->whereIn('RefInstructionId',$request->ids)->delete();
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

