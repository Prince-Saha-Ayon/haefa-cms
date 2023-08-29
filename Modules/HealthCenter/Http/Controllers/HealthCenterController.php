<?php

namespace Modules\HealthCenter\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthCenter\Entities\HealthCenter;
use Modules\Base\Http\Controllers\BaseController;
use Modules\HealthCenter\Http\Requests\HealthCenterFormRequest;
use Illuminate\Support\Str;
use DB;

class HealthCenterController extends BaseController
{
    protected $model;

    public function __construct(HealthCenter $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (permission('healthcenter-access')) {
            $this->setPageData('HealthCenter', 'HealthCenter', 'fas fa-th-list');
            return view('healthcenter::index');
        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */
    public function get_datatable_data(Request $request)
    {
        if (permission('healthcenter-access')) {
            if ($request->ajax()) {

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

                    if (permission('healthcenter-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->HealthCenterId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('healthcenter-view')) {
                        // $action .= ' <a class="dropdown-item view_data" data-HealthCenterId="' . $value->HealthCenterId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if (permission('healthcenter-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->HealthCenterId . '" data-name="' . $value->HealthCenterId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if (permission('healthcenter-bulk-delete')) {
                        $row[] = table_checkbox($value->HealthCenterId);
                    }

                    $row[] = $no;
                    $row[] = $value->HealthCenterCode;
                    $row[] = $value->HealthCenterName;
                    // $row[] = permission('healthcenter-edit') ? change_status($value->HealthCenterId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(),
                    $this->model->count_filtered(), $data);
            } else {
                $output = $this->access_blocked();
            }

            return response()->json($output);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $HealthCenterId
     * @return Renderable
     */
    public function store_or_update_data(HealthCenterFormRequest $request)
    {
        if ($request->ajax()) {
            if (permission('HealthCenter-add') || permission('healthcenter-edit')) {
                try {
                    $collection = collect($request->validated());
                    if (isset($request->HealthCenterId) && !empty($request->HealthCenterId)) {
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->HealthCenterId, $collection);
                        $result = $this->model->where('HealthCenterId', $request->HealthCenterId)->update($collection->all());
                        $output = $this->store_message($result, $request->HealthCenterId);
                        return response()->json($output);
                    } else {
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->HealthCenterId, $collection);
                        //update existing index value
                        $collection['HealthCenterId'] = Str::uuid();
                        $result = $this->model->create($collection->all());
                        $output = $this->store_message($result, $request->HealthCenterId);
                        return response()->json($output);
                    }

                } catch (\Exception $e) {
//                     return response()->json(['status'=>'error','message'=>$e->getMessage()]);
                    return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
                }

            } else {
                $output = $this->access_blocked();
                return response()->json($output);
            }

        } else {
            return response()->json($this->access_blocked());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $HealthCenterId
     * @return Renderable
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('healthcenter-delete')) {
                $result = $this->model->where('HealthCenterId', $request->id)->delete();
                $output = $this->store_message($result, $request->id);
                return response()->json($output);
            } else {
                return response()->json($this->access_blocked());
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
        }
    }

    /**
     * Status update
     * @return success or fail message
     */

    public function change_status(Request $request)
    {
        try {
            if ($request->ajax()) {
                if (permission('healthcenter-edit')) {
                    $result = $this->update_change_status($request);
                    if ($result) {
                        return response()->json(['status' => 'success', 'message' => 'Status Changed Successfully']);
                    } else {
                        return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
                    }
                } else {
                    $output = $this->access_blocked();
                    return response()->json($output);
                }
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
            }
        } catch (\Exception $e) {
            // return response()->json(['status'=>'error','message'=>'Something went wrong!']);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('HealthCenterId', $request->HealthCenterId)->update(['Status' => $request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $HealthCenterId
     * @return Renderable
     */
    public function show(Request $request)
    {
        if (permission('healthcenter-view')) {
            if ($request->ajax()) {
                if (permission('healthcenter-view')) {
                    $HealthCenters = DB::table('HealthCenter')->where('HealthCenterId', '=', $request->HealthCenterId)->first();
                }
            }
            return view('healthcenter::details', compact('HealthCenters'))->render();

        } else {
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $HealthCenterId
     * @return Renderable
     */
    public function edit(Request $request)
    {
        return $data = DB::table('HealthCenter')->where('HealthCenterId', $request->id)->first();
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ajax()) {
            try {
                if (permission('healthcenter-bulk-delete')) {
                    $result = $this->model->whereIn('HealthCenterId', $request->HealthCenterIds)->delete();
                    $output = $this->bulk_delete_message($result);
                } else {
                    $output = $this->access_blocked();
                }
                return response()->json($output);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
            }
        } else {
            return response()->json($this->access_blocked());
        }
    }
}



