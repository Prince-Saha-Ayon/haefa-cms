<?php

namespace Modules\BarcodeFormat\Http\Controllers;
use Illuminate\Http\Request;
use Modules\BarcodeFormat\Entities\BarcodeFormat;
use Modules\Base\Http\Controllers\BaseController;
use Modules\BarcodeFormat\Http\Requests\BarcodeFormRequest;
use Modules\BarcodeFormat\Entities\District;
use Modules\BarcodeFormat\Entities\Upazila;
use Modules\BarcodeFormat\Entities\Union;
use Modules\BarcodeFormat\Entities\HealthCenter;

class BarcodeFormatController extends BaseController
{
    public function __construct(BarcodeFormat $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('bformat-access')){
            $this->setPageData('Barcode Format','Barcode Format','fas fa-th-list');
            $data = [
                'districts' => District::all(),
                'upazilas' => Upazila::all(),
                'unions' => Union::all(),
                'HealthCenters' => HealthCenter::all(),
            ];
            return view('barcodeformat::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    public function get_datatable_data(Request $request)
    {
        if(permission('bformat-access')){
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

                    if(permission('bformat-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if(permission('bformat-delete')){
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->barcode_prefix . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }


                    $row = [];

                    if(permission('bformat-bulk-delete')){
                        $row[] = table_checkbox($value->id);
                    }
                    $row[] = $no;
                    $row[] = $value->barcode_prefix.''.$value->barcode_number;
                    $row[] = $value->district->districtName;
                    $row[] = $value->upazila->UpazilaName;
                    $row[] = $value->union->UnionName;
                    $row[] = $value->healthCenter->HealthCenterName;
                    $row[] = permission('bformat-edit') ? change_status($value->id,$value->status,$value->barcode_prefix) : STATUS_LABEL[$value->status];
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

    public function store_or_update_data(BarcodeFormRequest $request)
    {
        if($request->ajax()){
            if(permission('bformat-add') || permission('bformat-edit')){
                $collection = collect($request->validated());
                $collection = $this->track_data($request->update_id,$collection);
                $result = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                $output = $this->store_message($result,$request->update_id);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
           return response()->json($this->access_blocked());
        }
    }

    public function edit(Request $request)
    {
        if($request->ajax()){
            if(permission('bformat-edit')){
                $data = $this->model->findOrFail($request->id);
                $output = $this->data_message($data);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(permission('bformat-delete')){
                $result = $this->model->find($request->id)->delete();
                $output = $this->delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function bulk_delete(Request $request)
    {
        if($request->ajax()){
            if(permission('bformat-bulk-delete')){
                $result = $this->model->destroy($request->ids);
                $output = $this->bulk_delete_message($result);
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }

    public function change_status(Request $request)
    {
        if($request->ajax()){
            if (permission('bformat-edit')) {
                $result = $this->model->find($request->id)->update(['status'=>$request->status]);
                $output = $result ? ['status'=>'success','message'=>'Status has been changed successfully']
                : ['status'=>'error','message'=>'Failed to change status'];
            }else{
                $output = $this->access_blocked();
            }
            return response()->json($output);
        }else{
            return response()->json($this->access_blocked());
        }
    }
}
