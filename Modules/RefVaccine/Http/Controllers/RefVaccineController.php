<?php

namespace Modules\RefVaccine\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RefVaccine\Entities\RefVaccine;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\Base\Http\Controllers\BaseController;
use Modules\RefVaccine\Http\Requests\RefVaccineFormRequest;
use Illuminate\Support\Str;
use DB;
use Modules\RefVaccine\Http\Requests\RefVaccineRequest;

class RefVaccineController extends BaseController
{
    protected $model;

    public function __construct(RefVaccine $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (permission('refvaccine-access')) {
            $this->setPageData('RefVaccineChild', 'RefVaccineChild', 'fas fa-th-list');
            return view('refvaccine::index');
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
        if (permission('refvaccine-access')) {
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

                    if (permission('refvaccine-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->VaccineId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('refvaccine-view')) {
                        // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->VaccineId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if (permission('refvaccine-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->VaccineId . '" data-name="' . $value->VaccineId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if (permission('refvaccine-bulk-delete')) {
                        $row[] = table_checkbox($value->VaccineId);
                    }

                    $row[] = $no;
                    $row[] = $value->VaccineCode??'';
                    $row[] = $value->VaccineDoseNumber??'';
                    // $row[] = permission('refvaccine-edit') ? change_status($value->VaccineId,$value->Status,'refdepartment') : STATUS_LABEL[$value->Status];
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
                if (permission('refvaccine-edit')) {
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
        if (permission('refvaccine-view')) {
            if ($request->ajax()) {
                if (permission('refvaccine-view')) {
                    $RefVaccine = DB::select("SELECT  rd.DesignationTitle,rd.Description,
                    wp.WorkPlaceName,rfd.DepartmentCode
                    FROM RefVaccine AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.VaccineId='$request->id'");
                }
            }
            return view('refvaccine::details', compact('RefVaccine'))->render();

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
                    FROM RefVaccine AS rd
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
    public function store_or_update_data(RefVaccineRequest $request)
    {
        if ($request->ajax()) {
            if (permission('refvaccine-add') || permission('refvaccine-edit')) {
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
            if (permission('refvaccine-delete')) {
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
                if (permission('refvaccine-bulk-delete')) {
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

