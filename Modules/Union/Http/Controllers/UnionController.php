<?php

namespace Modules\Union\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Union\Entities\Union;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Union\Http\Requests\UnionFormRequest;
use Illuminate\Support\Str;
use DB;

class UnionController extends BaseController
{
    protected $model;
    public function __construct(Union $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('union-access')){
            $this->setPageData('Union','Union','fas fa-th-list');
            return view('union::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('union-access')){
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

                    if(permission('union-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->Id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('union-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->Id . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('union-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->Id . '" data-name="' . $value->Id . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('union-bulk-delete')){
                        $row[] = table_checkbox($value->Id);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->UnionName;
                    $row[] = $value->ShortName;
                    // $row[] = permission('union-edit') ? change_status($value->Id,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(UnionFormRequest $request)
    {
        if($request->ajax()){
            if(permission('Union-add') || permission('union-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->Id) && !empty($request->Id)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->Id,$collection);
                    $result = $this->model->where('Id', $request->Id)->update($collection->all());
                    $output = $this->store_message($result,$request->Id);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->Id,$collection);
                    //update existing index value
                    $collection['Id'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->Id);
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
            if (permission('union-delete')) {
                $result = $this->model->where('Id',$request->id)->delete();
                $output = $this->store_message($result,$request->Id);
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
                if (permission('union-edit')) {
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
        return $this->model->where('Id',$request->Id)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('union-view')){
            if($request->ajax()){
                if (permission('union-view')) {
                    $Unions= union::where('Id','=',$request->id)->first(); 
                }
            }
            return view('union::details',compact('Unions'))->render();
        
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
        return $data = DB::table('Union')->where('Id',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('union-bulk-delete')){
                    $result = $this->model->whereIn('Id',$request->ids)->delete();
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

