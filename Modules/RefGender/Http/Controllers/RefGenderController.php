<?php

namespace Modules\RefGender\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefGender\Entities\RefGender;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefGender\Http\Requests\RefGenderFormRequest;
use Illuminate\Support\Str;
use DB;

class RefGenderController extends BaseController
{
    protected $model;
    public function __construct(RefGender $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refgender-access')){
            $this->setPageData('Refgender','Refgender','fas fa-th-list');
            return view('refgender::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refgender-access')){
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

                    if(permission('refgender-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->GenderId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refgender-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->GenderId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refgender-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->GenderId . '" data-name="' . $value->GenderId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refgender-bulk-delete')){
                        $row[] = table_checkbox($value->GenderId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->GenderCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refgender-edit') ? change_status($value->GenderId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefGenderFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refgender-add') || permission('refgender-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->GenderId) && !empty($request->GenderId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->GenderId,$collection);
                    $result = $this->model->where('GenderId', $request->GenderId)->update($collection->all());
                    $output = $this->store_message($result,$request->GenderId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->GenderId,$collection);
                    //update existing index value
                    $collection['GenderId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->GenderId);
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
            if (permission('refgender-delete')) {
                $result = $this->model->where('GenderId',$request->id)->delete();
                $output = $this->store_message($result,$request->GenderId);
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
                if (permission('refgender-edit')) {
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
        return $this->model->where('GenderId',$request->GenderId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refgender-view')){
            if($request->ajax()){
                if (permission('refgender-view')) {
                    $RefGenders= RefGender::where('GenderId','=',$request->id)->first(); 
                }
            }
            return view('refgender::details',compact('RefGenders'))->render();
        
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
        return $data = DB::table('RefGender')->where('GenderId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refgender-bulk-delete')){
                    $result = $this->model->whereIn('GenderId',$request->ids)->delete();
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
