<?php

namespace Modules\RefVaccineAdult\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefVaccine\Http\Requests\RefVaccineRequest;
use Modules\RefVaccineAdult\Entities\RefVaccineAdult;
use Modules\RefVaccineAdult\Http\Requests\RefVaccineAdultRequest;
use Illuminate\Support\Str;
use DB;

class RefVaccineAdultController extends BaseController
{
    protected $model;

    public function __construct(RefVaccineAdult $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (permission('refvaccineadult-access')) {
            $this->setPageData('Vaccine Adult', 'Vaccine Adult', 'fas fa-th-list');
            return view('refvaccineadult::index');
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
        if (permission('refvaccineadult-access')) {
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

                    if (permission('refvaccineadult-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->VaccineId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('refvaccineadult-view')) {
                        // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->VaccineId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if (permission('refvaccineadult-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->VaccineId . '" data-name="' . $value->VaccineId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if (permission('refvaccineadult-bulk-delete')) {
                        $row[] = table_checkbox($value->VaccineId);
                    }

                    $row[] = $no;
                    $row[] = $value->VaccineCode ?? '';
                    $row[] = $value->VaccineDoseNumber ?? '';
                    // $row[] = permission('refvaccineadult-edit') ? change_status($value->VaccineId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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

    public function change_status(Request $request)
    {
        try {
            if ($request->ajax()) {
                if (permission('refvaccineadult-edit')) {
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
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update_change_status(Request $request)
    {
        return $this->model->where('VaccineId', $request->VaccineId)->update(['Status' => $request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if (permission('refvaccineadult-view')) {
            if ($request->ajax()) {
                if (permission('refvaccineadult-view')) {
                    $RefVaccine = DB::select("SELECT  rd.DesignationTitle,rd.Description,
                    wp.WorkPlaceName,rfd.DepartmentCode
                    FROM RefVaccineAdult AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.VaccineId='$request->id'");
                }
            }
            return view('refvaccineadult::details', compact('RefVaccine'))->render();

        } else {
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
        $data1 = DB::select("SELECT  rd.DesignationTitle,rd.Description,rd.RefDepartmentId,
                    wp.WorkPlaceName,rfd.DepartmentCode,rd.VaccineId,rd.WorkPlaceId
                    FROM RefVaccineAdult AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.VaccineId='$request->id'");

        $data2 = DB::select("SELECT * FROM WorkPlace");
        $data3 = DB::select("SELECT * FROM RefDepartment");

        return response()->json(['designation' => $data1, 'workplaces' => $data2, 'departments' => $data3]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(RefVaccineAdultRequest $request)
    {
        if ($request->ajax()) {
            if (permission('refvaccineadult-add') || permission('refvaccineadult-edit')) {
                try {
                    $collection = collect($request->validated());
                    if (isset($request->VaccineId) && !empty($request->VaccineId)) {
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->VaccineId, $collection);
                        $result = $this->model->where('VaccineId', $request->VaccineId)
                            ->update($collection->all());
                        $output = $this->store_message($result, $request->VaccineId);
                        return response()->json($output);
                    } else {
                        $collection = collect($request->all());
                        //track_data from base controller to merge created_by and created_at merge with request data
                        $collection = $this->track_data_org($request->VaccineId, $collection);
                        //update existing index value
                        $collection['VaccineId'] = Str::uuid();
                        $result = $this->model->create($collection->all());
                        $output = $this->store_message($result, $request->VaccineId);
                        return response()->json($output);
                    }

                } catch (\Exception $e) {
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
     * @param int $id
     * @return Renderable
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('refvaccineadult-delete')) {
                $result = $this->model->where('VaccineId', $request->id)->delete();
                $output = $this->store_message($result, $request->VaccineId);
                return response()->json($output);
            } else {
                return response()->json($this->access_blocked());
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
        }
    }

    public function bulk_delete(Request $request)
    {
        if ($request->ajax()) {
            try {
                if (permission('refvaccineadult-bulk-delete')) {
                    $result = $this->model->whereIn('RefDepartmentId', $request->ids)->delete();
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


