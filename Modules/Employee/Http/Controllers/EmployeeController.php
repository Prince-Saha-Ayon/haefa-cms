<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\Employee;
use Modules\RefDepartment\Entities\RefDepartment;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Employee\Http\Requests\EmployeeFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class EmployeeController extends BaseController
{
    protected $model;
    public function __construct(Employee $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(permission('employee-access')){
            $this->setPageData('Employee','Employee','fas fa-th-list');
            $data['genders'] = DB::select("SELECT * FROM RefGender");
            $data['maritalStatus'] = DB::select("SELECT * FROM RefMaritalStatus");
            $data['educations'] = DB::select("SELECT * FROM RefEducation");
            $data['religions'] = DB::select("SELECT * FROM RefReligion");
            $data['roles'] = DB::select("SELECT * FROM Role");
            $data['workplaces'] = DB::select("SELECT * FROM WorkPlace");
            $data['departments'] = DB::select("SELECT * FROM RefDepartment");
            return view('employee::index',$data);
        }else{
            return $this->unauthorized_access_blocked();
        }
    }

    /**
     * Show data table data
     * @return $data
     */

    public function get_datatable_data(Request $request)
    {
        if (permission('employee-access')) {
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

                    if (permission('employee-edit')) {
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->EmployeeId . '"><i class="fas fa-edit text-primary"></i> Edit</a>';
                    }
                    if (permission('employee-view')) {
                        // $action .= ' <a class="dropdown-item view_data" data-id="' . $value->EmployeeId . '"><i class="fas fa-eye text-success"></i> View</a>';
                    }
                    if (permission('employee-delete')) {
                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->EmployeeId . '" data-name="' . $value->EmployeeId . '"><i class="fas fa-trash text-danger"></i> Delete</a>';
                    }

                    $row = [];

                    if (permission('employee-bulk-delete')) {
                        $row[] = table_checkbox($value->EmployeeId);
                    }

                    $row[] = $no;
                    $row[] = $value->EmployeeCode;
                    $row[] = $value->RegistrationNumber;
                    $row[] = $value->FirstName;
                    $row[] = $value->LastName;
                    $row[] = action_button($action);
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
            } else {
                $output = $this->access_blocked();
                return response()->json($output);
            }
        }
    }


    public function change_status(Request $request)
    {
        try{
            if($request->ajax()){
            if (permission('employee-edit')) {
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
        return $this->model->where('EmployeeId',$request->EmployeeId)->update(['Status'=>$request->Status]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Request $request)
    {
        if(permission('employee-view')){
            if($request->ajax()){
                if (permission('employee-view')) {
                    $Employee = DB::select("SELECT  rd.DesignationTitle,rd.Description,
                    wp.WorkPlaceName,rfd.DepartmentCode
                    FROM Employee AS rd
                    INNER JOIN WorkPlace AS wp ON rd.WorkPlaceId = wp.WorkPlaceId
                    INNER JOIN RefDepartment AS rfd ON rd.RefDepartmentId = rfd.RefDepartmentId
                    WHERE rd.EmployeeId='$request->id'");
                }
            }
            return view('employee::details',compact('Employee'))->render();

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
         $data1 = DB::select("SELECT em.EmployeeId,em.OrgId,em.EmployeeCode,em.RegistrationNumber,em.FirstName,em.FirstName,em.LastName,em.LastName,em.GenderId,em.BirthDate,
         em.JoiningDate,em.MaritalStatusId,em.EducationId,em.Designation,em.ReligionId,em.RoleId,em.Email,em.Phone,em.NationalIdNumber,em.EmployeeImage,em.EmployeeSignature,
         em.Status,gnd.GenderCode,mar.MaritalStatusCode,edu.EducationCode,reli.ReligionCode,rl.RoleCode
         FROM Employee AS em
         LEFT JOIN RefGender AS gnd ON em.GenderId = gnd.GenderId
         LEFT JOIN RefMaritalStatus AS mar ON mar.MaritalStatusId = em.MaritalStatusId
         LEFT JOIN RefEducation as edu ON edu.EducationId = em.EducationId
         LEFT JOIN RefReligion as reli ON reli.ReligionId = em.ReligionId
         LEFT JOIN Role rl ON rl.RoleId = em.RoleId
         WHERE em.EmployeeId='$request->id'");

         $data2 = DB::select("SELECT * FROM RefGender");
         $data3 = DB::select("SELECT * FROM RefMaritalStatus");
         $data4 = DB::select("SELECT * FROM RefEducation");
         $data5 = DB::select("SELECT * FROM RefReligion");
         $data6 = DB::select("SELECT * FROM Role");

         return response()->json(['employee'=>$data1,'genders'=>$data2,
         'maritalStatus'=> $data3,'educations'=>$data4,'religions'=>$data5,
         'roles'=>$data6]);

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function store_or_update_data(EmployeeFormRequest $request)
    {
        if($request->ajax()){
            if(permission('employee-add') || permission('employee-edit')){
                try{
                    $collection = collect($request->validated());
                    if($collection){
                        $output = ['status'=>'error','message'=>'Please provide Employee Code'];
                    }
                    if(isset($request->EmployeeId) && !empty($request->EmployeeId)){
                        $employee = DB::table('Employee')->where('EmployeeId',$request->EmployeeId)->first();

                        $file = $request->EmployeeImage;

                        if(!empty($file) && $file !=='undefined'){
                            $mimeType = $file->getMimeType();
                            $EmployeeImage = base64_encode(file_get_contents($file));
                            $EmployeeImageBase64Link = 'data:' . $mimeType . ';base64,' . $EmployeeImage;
                        }else{
                            $EmployeeImageBase64Link = $employee->EmployeeImage??'';
                        }

                        $file2 = $request->EmployeeSignature;
                        if(!empty($file2) && $file2 !=='undefined'){
                            $mimeType = $file2->getMimeType();
                            $EmployeeSignature = base64_encode(file_get_contents($file2));
                            $EmployeeSignatureBase64Link = 'data:' . $mimeType . ';base64,' . $EmployeeSignature;
                        }else{
                            $EmployeeSignatureBase64Link = $employee->EmployeeSignature??'';
                        }

                        DB::table('Employee')->where('EmployeeId',$request->EmployeeId)->update([
                            'EmployeeId'=>(string) Str::uuid(),
                            'OrgId'=>(string)auth()->user()->OrgId,
                            'EmployeeCode'=>$request->EmployeeCode,
                            'RegistrationNumber'=>$request->RegistrationNumber,
                            'FirstName'=>$request->FirstName,
                            'LastName'=>$request->LastName,
                            'GenderId'=>$request->GenderId,
                            'BirthDate'=>$request->BirthDate,
                            'JoiningDate'=>$request->JoiningDate,
                            'MaritalStatusId'=>$request->MaritalStatusId,
                            'Designation'=>$request->Designation,
                            'ReligionId'=>$request->ReligionId,
                            'RoleId'=>$request->RoleId,
                            'Email'=>$request->Email,
                            'Phone'=>$request->Phone,
                            'NationalIdNumber'=>$request->NationalIdNumber,
                            'EmployeeImage'=>$EmployeeImageBase64Link,
                            'EmployeeSignature'=>$EmployeeSignatureBase64Link,
                            'Status'=>1,
                            'UpdateUser'=>Auth::user()->id??'',
                            'UpdateDate'=>Carbon::now()
                        ]);
                        $output = ['status'=>'success','message'=>'Data has been updated successfully!'];
                        return response()->json($output);
                    }
                    else{

                        $file = $request->EmployeeImage;

                        if($file && $file !=='undefined'){
                            $mimeType = $file->getMimeType();
                            $EmployeeImage = base64_encode(file_get_contents($file));
                            $EmployeeImageBase64Link = 'data:' . $mimeType . ';base64,' . $EmployeeImage;
                        }else{
                            $EmployeeImageBase64Link ="";
                        }

                        $file2 = $request->EmployeeSignature;
                        if($file2 && $file2 !=='undefined'){
                            $mimeType = $file2->getMimeType();
                            $EmployeeSignature = base64_encode(file_get_contents($file2));
                            $EmployeeSignatureBase64Link = 'data:' . $mimeType . ';base64,' . $EmployeeSignature;
                        }else{
                            $EmployeeSignatureBase64Link ="";
                        }

                        DB::table('Employee')->insert([
                            'EmployeeId'=> Str::uuid(),
                            'OrgId'=> auth()->user()->OrgId,
                            'EmployeeCode'=>$request->EmployeeCode,
                            'RegistrationNumber'=>$request->RegistrationNumber,
                            'FirstName'=>$request->FirstName,
                            'LastName'=>$request->LastName,
                            'GenderId'=>$request->GenderId,
                            'BirthDate'=>$request->BirthDate,
                            'JoiningDate'=>$request->JoiningDate,
                            'MaritalStatusId'=>$request->MaritalStatusId,
                            'Designation'=>$request->Designation,
                            'ReligionId'=>$request->ReligionId,
                            'RoleId'=>$request->RoleId,
                            'Email'=>$request->Email,
                            'Phone'=>$request->Phone,
                            'NationalIdNumber'=>$request->NationalIdNumber,
                            'EmployeeImage'=>$EmployeeImageBase64Link,
                            'EmployeeSignature'=>$EmployeeSignatureBase64Link,
                            'Status'=>1,
                            'CreateUser'=>Auth::user()->id??'',
                            'CreateDate'=>Carbon::now()
                        ]);
                        $output = ['status'=>'success','message'=>'Data has been saved successfully!'];
                        return response()->json($output);
                    }

                }catch(\Exception $e){
                    // return response()->json(['status'=>'error','message'=>'Something went wrong !']);
                    return response()->json(['status'=>'error','message'=>$e->getMessage()]);
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
            if (permission('employee-delete')) {
                $result = $this->model->where('EmployeeId',$request->id)->delete();
                $output = $this->delete_message($result,$request->id);
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
                if(permission('employee-bulk-delete')){
                    $result = $this->model->whereIn('RefDepartmentId',$request->ids)->delete();
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
