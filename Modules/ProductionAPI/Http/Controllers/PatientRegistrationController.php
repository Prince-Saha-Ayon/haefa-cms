<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\ApiPatientTrigView;
use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

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
           
               if(permission('patient-registration-access')){
                     $facilities=RefApiFacility::get();
                    $this->setPageData('Patient Registartion API','Patient Registartion API','fas fa-th-list');
                     return view('productionapi::patient-registration',compact('facilities'));
                }else{
                    return $this->unauthorized_access_blocked();
                }

            // $this->setPageData('Patient Registartion API','Patient Registartion API','fas fa-th-list');
            // return view('productionapi::patient-registration',compact('facilities'));
        
    }



public function register(Request $request)
{
    // Authenticate and get the access token
    $identifier = $request->identifier;
    $sending_patient = intval($request->send_patient);

    $authData = ApiHelper::authenticate();

    if (isset($authData['error'])) {
        // Handle authentication error
        return response()->json(['error' => $authData['error']], 401);
    }

    $accessToken = $authData['access_token'];

    // Retrieve patients from the database


    // Prepare arrays to store success and error responses
    $responses = ApiHelper::SendRegistrationPayload($accessToken, $identifier, $sending_patient);
    $successResponses= $responses['successResponses'];
    $errorResponses= $responses['errorResponses'];

    // Handle successful registration
    return response()->json([
        'success' => $successResponses,
        'error' => $errorResponses
    ]);
}

    public function GetCount(Request $request)
    {
        $identifier=$request->identifier;
        $unsent = ApiPatientTrigView::where('Facility_identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

