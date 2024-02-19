<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\ApiConditionTrigView;

use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

class PatientConditionController extends BaseController
{
     protected $model;
    public function __construct(ApiConditionTrigView $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {



        if(permission('patient-condition-access')){
            $facilities=RefApiFacility::get();
            $this->setPageData('Patient Condition API','Patient Condition API','fas fa-th-list');
            return view('productionapi::patient-condition',compact('facilities'));
        }else{
                    return $this->unauthorized_access_blocked();
        }

        
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
          $patients = ApiConditionTrigView::where('identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();
       

              

        // Prepare patient data array
    

    $successResponses = [];
    $errorResponses = [];

        // Loop through the patients and format the data
    foreach ($patients as $patientcondition) {
    $patientData = [
    "resourceType" => "Condition",
    "meta" => [
        "lastUpdated" => $patientcondition->UpdateDate ? \Carbon\Carbon::parse($patientcondition->UpdateDate)->toIso8601String() : null,
        "createdAt" => $patientcondition->CreateDate ? \Carbon\Carbon::parse($patientcondition->CreateDate)->toIso8601String() : null
    ],
    "identifier" => [
        [
            "value" => $patientcondition->MDataPatientId // Assuming you have a unique identifier for the condition
        ]
    ],
    "subject" => [
        "identifier" => $patientcondition->PatientId // Assuming you have a PatientId field in your Patient model
    ],
    "code" => [
        "coding" => []
    ]
];

$conditionToCode = [
    "Diabetes Mellitus" => "73211009",
    "Hypertension" => "38341003"
];
// Assuming you have an array of condition codes
$processedConditions = [];

// Explode the string of conditions into an array
$conditionsArray = explode(", ", $patientcondition->Condition);

// Loop through the patient's conditions and add them to the payload
foreach ($conditionsArray as $condition) {
    // Check if the condition exists in the mapping and has not been processed already
    if (isset($conditionToCode[$condition]) && !in_array($condition, $processedConditions)) {
        // Add the condition to the payload
        $patientData["code"]["coding"][] = [
            "system" => "http://snomed.info/sct",
            "code" => $conditionToCode[$condition]
        ];
        // Add the condition to the processed conditions array
        $processedConditions[] = $condition;
    }
}
         $conditionResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

        if (isset($bpResponse['error'])) {
            // Handle registration error
            ApiPatientList::where('PatientId', $patientcondition->PatientId)->update(['ConditionStatus' => 'error']);
            $errorResponses[] = ['error' => $conditionResponse['error']];
        } elseif ($conditionResponse['status'] == 202) {
            // Update registration status for successful registration
            ApiPatientList::where('PatientId', $patientcondition->PatientId)->update(['ConditionStatus' => 'sent']);
            $successResponses[] = ['message' => 'Patient Condition successfully'];
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
        $unsent = ApiConditionTrigView::where('identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

