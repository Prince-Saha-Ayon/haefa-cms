<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\ApiBpTrigView;

use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

class PatientBpController extends BaseController
{
     protected $model;
    public function __construct(ApiBpTrigView $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
            
                if(permission('patient-bp-access')){
                   $facilities=RefApiFacility::get();
                    $this->setPageData('Patient Blood Pressure API','Patient Blood Pressure API','fas fa-th-list');
                    return view('productionapi::patient-bp',compact('facilities'));
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
          $patients = ApiBpTrigView::where('identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();
       

              

        // Prepare patient data array
    

    $successResponses = [];
    $errorResponses = [];

        // Loop through the patients and format the data
    foreach ($patients as $patientbp) {
          $patientData = [
          "resourceType" => "Observation",
        "meta" => [
                "lastUpdated" => $patientbp->UpdateDate ? \Carbon\Carbon::parse($patientbp->UpdateDate)->toIso8601String() : null,
                "createdAt" => $patientbp->CreateDate ? \Carbon\Carbon::parse($patientbp->CreateDate)->toIso8601String() : null
            ],
        "identifier" => [
            [
                "value" =>$patientbp->MdataBPId 
            ]
        ],
        "subject" => [
            "identifier" => $patientbp->PatientId 
        ],
        "performer" => [
            [
                "identifier" =>  $patientbp->identifier
            ]
        ],
        "effectiveDateTime" => $patientbp->CreateDate ? \Carbon\Carbon::parse($patientbp->CreateDate)->toIso8601String() : null,
        "code" => [
            "coding" => [
                [
                    "system" => "http://loinc.org",
                    "code" => "85354-9"
                ]
            ]
        ],
        "component" => [
            [
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "8480-6"
                        ]
                    ]
                ],
                "valueQuantity" => [
                    "value" => intval($patientbp->BPSystolic1),
                    "unit" => "mmHg",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "mm[Hg]"
                ]
            ],
            [
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "8462-4"
                        ]
                    ]
                ],
                "valueQuantity" => [
                    "value" =>intval($patientbp->BPDiastolic1),
                    "unit" => "mmHg",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "mm[Hg]"
                ]
            ]
        ]
    ];
         $bpResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

        if (isset($bpResponse['error'])) {
            // Handle registration error
            ApiPatientList::where('PatientId', $patientbp->PatientId)->where('DiseaseName','Hypertension')->update(['BPStatus' => 'error']);
            $errorResponses[] = ['error' => $bpResponse['error']];
        } elseif ($bpResponse['status'] == 202) {
            // Update registration status for successful registration
            ApiPatientList::where('PatientId', $patientbp->PatientId)->where('DiseaseName','Hypertension')->update(['BPStatus' => 'sent']);
            $successResponses[] = ['message' => 'Patient BP sent successfully'];
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
        $unsent = ApiBpTrigView::where('identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

