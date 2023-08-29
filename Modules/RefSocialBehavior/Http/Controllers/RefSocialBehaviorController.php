<?php

namespace Modules\RefSocialBehavior\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Controller;
use Modules\RefSocialBehavior\Entities\RefSocialBehavior;
use Modules\RefSocialBehavior\Http\Requests\RefSocialBehaviorFormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\Base\Http\Controllers\BaseController;
use Illuminate\Support\Str;
use DB;

class RefSocialBehaviorController extends BaseController
{
    
    protected $model;
    public function __construct(RefSocialBehavior $model)
    {
        $this->model = $model;
    }
    
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('refsocialbehavior-access')){
            $this->setPageData('RefSocialBehavior','RefSocialBehavior','fas fa-th-list');
            return view('refsocialbehavior::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refsocialbehavior-access')){
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

                    if(permission('refsocialbehavior-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->SocialBehaviorId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('refsocialbehavior-view')){
                       // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->SocialBehaviorId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if(permission('refsocialbehavior-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->SocialBehaviorId . '" data-name="' . $value->SocialBehaviorId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }
                    
                    $row = [];

                    if(permission('refsocialbehavior-bulk-delete')){
                        $row[] = table_checkbox($value->SocialBehaviorId);
                    }
                   
                    $row[] = $no;
                    $row[] = $value->SocialBehaviorCode;
                    $row[] = $value->Description;
                    // $row[] = permission('refsocialbehavior-edit') ? change_status($value->SocialBehaviorId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
    public function store_or_update_data(RefSocialBehaviorFormRequest $request)
    {
        if($request->ajax()){
            if(permission('RefSocialBehavior-add') || permission('refsocialbehavior-edit')){
                try{
                    $collection = collect($request->validated());
                    if(isset($request->SocialBehaviorId) && !empty($request->SocialBehaviorId)){
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->SocialBehaviorId,$collection);
                    $result = $this->model->where('SocialBehaviorId', $request->SocialBehaviorId)->update($collection->all());
                    $output = $this->store_message($result,$request->SocialBehaviorId);
                    return response()->json($output);
                }
                else{
                    $collection = collect($request->all());
                    //track_data from base controller to merge created_by and created_at merge with request data
                    $collection = $this->track_data_org($request->SocialBehaviorId,$collection);
                    //update existing index value
                    $collection['SocialBehaviorId'] = Str::uuid();
                    $result = $this->model->create($collection->all());
                    $output = $this->store_message($result,$request->SocialBehaviorId);
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
            if (permission('refsocialbehavior-delete')) {
                $result = $this->model->where('SocialBehaviorId',$request->id)->delete();
                $output = $this->store_message($result,$request->SocialBehaviorId);
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
            if (permission('refsocialbehavior-edit')) {
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
        return $this->model->where('SocialBehaviorId',$request->SocialBehaviorId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refsocialbehavior-view')){
            if($request->ajax()){
                if (permission('refsocialbehavior-view')) {
                    $RefSocialBehaviors= refsocialbehavior::where('SocialBehaviorId','=',$request->id)->first(); 
                }
            }
            return view('refsocialbehavior::details',compact('RefSocialBehaviors'))->render();
        
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
        return $data = DB::table('RefSocialBehavior')->where('SocialBehaviorId',$request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            try{
                if(permission('refsocialbehavior-bulk-delete')){
                    $result = $this->model->whereIn('SocialBehaviorId',$request->ids)->delete();
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

