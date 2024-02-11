<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;

class PatientRegistrationController extends BaseController
{
     protected $model;
    public function __construct(PatientRegistration $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
            $facilities=RefApiFacility::get();
            $sent = ApiPatientList::where('Status', 'sent')->count();
            $unsent = ApiPatientList::where('Status', 'unsent')->count();

            $this->setPageData('Patient Registartion API','Patient Registartion API','fas fa-th-list');
            return view('productionapi::patient-registration',compact('facilities','sent','unsent'));
        
    }

    /**
     * Show data table data
     * @return $data
     */


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */


    /**
     * Status update
     * @return success or fail message
     */




    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
  


}

