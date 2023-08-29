<?php

namespace Modules\RefMenstruationProduct\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefMenstruationProduct\Entities\RefMenstruationProduct;
use Modules\RefMenstruationProduct\Http\Requests\RefMenstruationProductFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefMenstruationProductController extends BaseController
{
    protected $model;
    public function __construct(RefMenstruationProduct $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refmenstruationproduct-access')){
            $this->setPageData('Ref Menstruation Product','Ref Menstruation Product','fas fa-th-list');
            return view('refmenstruationproduct::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refmenstruationproduct-access')){
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

                    if(permission('refmenstruationproduct-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->MenstruationProductId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refmenstruationproduct-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->MenstruationProductId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refmenstruationproduct-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->MenstruationProductId . '" data-name="' . $value->MenstruationProductId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refmenstruationproduct-bulk-delete')){
                        $row[] = table_checkbox($value->MenstruationProductId);
                    }
                    
                    $row[] = $no;
                    $row[] = $value->MenstruationProductCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refmenstruationproduct-edit') ? change_status($value->MenstruationProductId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
            if (permission('refmenstruationproduct-edit')) {
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
        return $this->model->where('MenstruationProductId',$request->MenstruationProductId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Request $request)
    {
        return $data = DB::table('RefMenstruationProduct')->where('MenstruationProductId',$request->id)->first();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefMenstruationProductFormRequest $request)
    {
        if($request->ajax()){
            if(permission('refmenstruationproduct-add') || permission('refmenstruationproduct-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->MenstruationProductId) && !empty($request->MenstruationProductId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->MenstruationProductId,$collection);
                    $result = $this->model->where('MenstruationProductId', $request->MenstruationProductId)->update($collection->all());
                    $output = $this->store_message($result,$request->MenstruationProductId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->MenstruationProductId,$collection);
                    //update existing index value
                    $collection['MenstruationProductId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->MenstruationProductId);
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
            if (permission('refmenstruationproduct-delete')) {
                $result = $this->model->where('MenstruationProductId',$request->id)->delete();
                $output = $this->store_message($result,$request->MenstruationProductId);
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
                if(permission('refmenstruationproduct-bulk-delete')){
                    $result = $this->model->whereIn('MenstruationProductId',$request->ids)->delete();
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


