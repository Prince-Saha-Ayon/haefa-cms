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
            $facilities=RefApiFacility::get();


            $this->setPageData('Patient Blood Pressure API','Patient Blood Pressure API','fas fa-th-list');
            return view('productionapi::patient-bp',compact('facilities'));
        
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
          $patients = ApiBpTrigView::where('Facility_identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();
       

              

        // Prepare patient data array
        $patientData = [];

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
                "identifier" =>  $patientbp->Facility_identifier
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
                    "value" => $patientbp->BPSystolic1,
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
                    "value" =>$patientbp->BPDiastolic1,
                    "unit" => "mmHg",
                    "system" => "http://unitsofmeasure.org",
                    "code" => "mm[Hg]"
                ]
            ]
        ]
    ];
      ApiPatientList::where('PatientId', $patientbp->PatientId)
            ->update(['Status' => 'sent']);
             
}

        // Wrap patient data array inside 'resources' key
        $patientData = ['resources' => $patientData];
   

        // Register the patients
        $registrationResponse = ApiHelper::registerPatient($accessToken, $patientData);
      

        if (isset($registrationResponse['error'])) {
            // Handle registration error
            return response()->json(['error' => $registrationResponse['error']], 500);
        }

        // Handle successful registration
        return response()->json(['message' => 'Patients registered successfully'], 200);
    }
    public function GetCount(Request $request)
    {
        $identifier=$request->identifier;
        $unsent = ApiBpTrigView::where('Facility_identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

