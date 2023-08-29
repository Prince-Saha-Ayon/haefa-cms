<?php

namespace Modules\RefAddressType\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefAddressType\Entities\RefAddressType;
use Modules\Base\Http\Controllers\BaseController;

class RefAddressTypeController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    protected $model;
    public function __construct(RefAddressType $model)
    {
        $this->model = $model;
    }

    public function index(){
        if(permission('refaddress-type-access')){
            $this->setPageData('RefAddressType','RefAddressType','fas fa-th-list');
            return view('refaddresstype::index');
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request){
        if(permission('refaddress-type-access')){
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

                    if(permission('prescription-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->AddressTypeId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('prescription-view')){
                        $action .= ' <a class="dropdown-item view_data" data-id="' . $value->AddressTypeId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    
                    $row = [];

                    if(permission('prescription-bulk-delete')){
                        $row[] = table_checkbox($value->AddressTypeId);
                    }
                    $row[] = $no;
                    $row[] = $value->AddressTypeCode;
                    $row[] = $value->Description;
                    $row[] = $value->SortOrder;
                    $row[] = $value->CreateDate;
                    //$row[] = permission('prescription-access') ? change_status($value->prescriptionId,$value->Status) : STATUS_LABEL[$value->Status];
                    //$row[] = permission('prescription-edit') ? change_status($value->prescriptionId,$value->Status) : STATUS_LABEL[$value->Status];
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
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('refaddresstype-access')){
            if($request->ajax()){
                if (permission('prescription-view')) {
                    $refAddressTypeDetails=RefAddressType::where('AddressTypeId','=',$request->id)->first();
                }
            }
            return view('refaddresstype::details',compact('refAddressTypeDetails'))->render();
        
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
        return $data = RefAddressType::where('AddressTypeId',$request->id)->first();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}