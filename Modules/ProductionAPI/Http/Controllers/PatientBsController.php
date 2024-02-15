<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\ApiBsTrigView;

use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

class PatientBsController extends BaseController
{
    protected $model;
    public function __construct(ApiBsTrigView $model)
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


            $this->setPageData('Patient Blood Sugar API','Patient Blood Sugar API','fas fa-th-list');
            return view('productionapi::patient-bs',compact('facilities'));

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
          $patients = ApiBsTrigView::where('identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();



        $successResponses = [];
        $errorResponses = [];


        // Prepare patient data array

        // Loop through the patients and format the data
        foreach ($patients as $patientBS) {
            if($patientBS->RBG != '' && $patientBS->FBG != '') {
                    $patientData = [
                "resourceType" => "Observation",
                "meta" => [
                    "lastUpdated" => $patientBS->UpdateDate ? \Carbon\Carbon::parse($patientBS->UpdateDate)->toIso8601String() : null,
                    "createdAt" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null
                ],
                "identifier" => [
                    [
                        "value" => $patientBS->MdataGlucoseId
                    ]
                ],
                "subject" => [
                    "identifier" => $patientBS->PatientId
                ],
                "performer" => [
                    [
                        "identifier" => $patientBS->identifier
                    ]
                ],
                "effectiveDateTime" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null,
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "2339-0"
                        ]
                    ]
                ],
                "component" => [
                    [
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://loinc.org",
                                    "code" => "2339-0"
                                ]
                            ]
                        ],
                        "valueQuantity" => [
                            "value" => intval($patientBS->RBG ),
                            "unit" => "mg/dL",
                            "system" => "http://unitsofmeasure.org",
                            "code" => "mg/dL"
                        ]
                    ]
                ]
            ];
            $registrationResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);
             $patientData = [
                "resourceType" => "Observation",
                "meta" => [
                    "lastUpdated" => $patientBS->UpdateDate ? \Carbon\Carbon::parse($patientBS->UpdateDate)->toIso8601String() : null,
                    "createdAt" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null
                ],
                "identifier" => [
                    [
                        "value" => $patientBS->MdataGlucoseId
                    ]
                ],
                "subject" => [
                    "identifier" => $patientBS->PatientId
                ],
                "performer" => [
                    [
                        "identifier" => $patientBS->identifier
                    ]
                ],
                "effectiveDateTime" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null,
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "2339-0"
                        ]
                    ]
                ],
                "component" => [
                    [
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://loinc.org",
                                    "code" => "88365-2"
                                ]
                            ]
                        ],
                        "valueQuantity" => [
                            "value" => intval($patientBS->FBG ),
                            "unit" => "mg/dL",
                            "system" => "http://unitsofmeasure.org",
                            "code" => "mg/dL"
                        ]
                    ]
                ]
            ];
             $registrationResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);
            }else{
                   $patientData = [
                "resourceType" => "Observation",
                "meta" => [
                    "lastUpdated" => $patientBS->UpdateDate ? \Carbon\Carbon::parse($patientBS->UpdateDate)->toIso8601String() : null,
                    "createdAt" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null
                ],
                "identifier" => [
                    [
                        "value" => $patientBS->MdataGlucoseId
                    ]
                ],
                "subject" => [
                    "identifier" => $patientBS->PatientId
                ],
                "performer" => [
                    [
                        "identifier" => $patientBS->identifier
                    ]
                ],
                "effectiveDateTime" => $patientBS->CreateDate ? \Carbon\Carbon::parse($patientBS->CreateDate)->toIso8601String() : null,
                "code" => [
                    "coding" => [
                        [
                            "system" => "http://loinc.org",
                            "code" => "2339-0"
                        ]
                    ]
                ],
                "component" => [
                    [
                        "code" => [
                            "coding" => [
                                [
                                    "system" => "http://loinc.org",
                                    "code" => $patientBS->FBG != '' ? "88365-2" : "2339-0",	
                                ]
                            ]
                        ],
                        "valueQuantity" => [
                            "value" => intval($patientBS->FBG != '' ? $patientBS->FBG : $patientBS->RBG ),
                            "unit" => "mg/dL",
                            "system" => "http://unitsofmeasure.org",
                            "code" => "mg/dL"
                        ]
                    ]
                ]
            ];
             $registrationResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

            }
         
        // Register the patient

       

        if (isset($registrationResponse['error'])) {
            // Handle registration error
            ApiPatientList::where('PatientId', $patientBS->PatientId)->where('DiseaseName','=','Diabetes Mellitus')->update(['GlucoseStatus' => 'error']);
            $errorResponses[] = ['error' => $registrationResponse['error']];
        } elseif ($registrationResponse['status'] == 202) {
            // Update registration status for successful registration
            ApiPatientList::where('PatientId', $patientBS->PatientId)->where('DiseaseName','=','Diabetes Mellitus')->update(['GlucoseStatus' => 'sent']);
            $successResponses[] = ['message' => 'Patient Blood Sugar successfully'];
        }

        }

        // Handle successful registration
        return response()->json([
            'success' => $successResponses,
            'error' => $errorResponses
        ]);
    }
    public function GetCount(Request $request)
    {
        $identifier=$request->identifier;
        $unsent = ApiBsTrigView::where('identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }
}
