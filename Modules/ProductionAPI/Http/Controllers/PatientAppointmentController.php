<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;

use Modules\ProductionAPI\Entities\ApiPatientVisitedTrigView;

use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

class PatientAppointmentController extends BaseController
{
     protected $model;
    public function __construct(ApiPatientVisitedTrigView $model)
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


            $this->setPageData('Patient Appointment Visited API','Patient Appointment Visited API','fas fa-th-list');
            return view('productionapi::patient-visited',compact('facilities'));
        
    }

     public function register(Request $request)
    {
        // Authenticate and get the access token
        $identifier=$request->identifier;
        $sending_patient=intval($request->send_patient);

            
 
        $authData = ApiHelper::authenticate();

        if (isset($authData['error'])) {
            // Handle authentication error
            return response()->json(['error' => $authData['error']], 401);
        }

        $accessToken = $authData['access_token'];

      

        // Retrieve 100 patients from the database
          $patients = ApiPatientVisitedTrigView::where('identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();
       

              

        // Prepare patient data array
    

    $successResponses = [];
    $errorResponses = [];

        // Loop through the patients and format the data
foreach ($patients as $patientvisited) {
    $patientData = [
        "resourceType" => "Appointment",
        "meta" => [
            "lastUpdated" => $patientvisited->CreateDate ? \Carbon\Carbon::parse($patientvisited->CreateDate)->toIso8601String() : null,
            "createdAt" => $patientvisited->CreateDate ? \Carbon\Carbon::parse($patientvisited->CreateDate)->toIso8601String() : null
        ], 
        "identifier" => [
            [
                "value" => $patientvisited->PrescriptionCreationId
            ]
        ],
        "status" => 'fulfilled',
        "start" => $patientvisited->StartDate ? \Carbon\Carbon::parse($patientvisited->StartDate)->toIso8601String() : null,
        "appointmentOrganization" => [
            "identifier" => $patientvisited->identifier
        ],
        "appointmentCreationOrganization" => null,
        "participant" => [
            [
                "actor" => [
                    "identifier" => $patientvisited->PatientId_Apl
                ]
            ]
        ]
    ];

    $appointmentResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

    if (isset($appointmentResponse['error'])) {
        // Handle registration error
        ApiPatientList::where('PatientId', $patientvisited->PatientId_Apl)->update(['VisitedStatus' => 'error']);
        $errorResponses[] = ['error' => $appointmentResponse['error']];
    } elseif ($appointmentResponse['status'] == 202) {
        // Update registration status for successful registration
        ApiPatientList::where('PatientId', $patientvisited->PatientId_Apl)->update(['VisitedStatus' => 'sent']);
        $successResponses[] = ['message' => 'Patient Appointment visited successfully'];
    }
}

       return response()->json([
        'success' => $successResponses,
        'error' => $errorResponses
    ]);
}
    public function GetCount(Request $request)
    {
        $identifier=$request->identifier;
        $unsent = ApiPatientVisitedTrigView::where('identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

