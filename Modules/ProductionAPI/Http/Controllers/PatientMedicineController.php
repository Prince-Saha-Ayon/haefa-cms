<?php

namespace Modules\ProductionAPI\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Base\Http\Controllers\BaseController;
use Modules\ProductionAPI\Entities\ApiPatientList;
use Modules\ProductionAPI\Entities\ApiBsTrigView;
use Modules\ProductionAPI\Entities\ApiMedicineTrigView;

use Modules\ProductionAPI\Entities\PatientRegistration;
use Modules\ProductionAPI\Entities\RefApiFacility;
use App\Helpers\ApiHelper;

class PatientMedicineController extends BaseController
{
    protected $model;
    public function __construct(ApiMedicineTrigView $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

           if(permission('patient-medicine-access')){
            $facilities=RefApiFacility::get();
            $this->setPageData('Patient Medicine API','Patient Medicine API','fas fa-th-list');
            return view('productionapi::patient-medicine',compact('facilities'));
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
          $patients = ApiMedicineTrigView::where('identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();



        $successResponses = [];
        $errorResponses = [];


        // Prepare patient data array

        // Loop through the patients and format the data
    foreach ($patients as $patientMed) {
       $timeUnit = $patientMed->DrugDurationValue;

// Define a mapping of time units to their equivalent days
        $timeUnitToDays = [
            'day' => 1,
            'days' => 1,
            'week' => 7,
            'weeks' => 7,
            'month' => 30,
            'months' => 30,
            'year' => 365,
            'years' => 365,
            'continue' => 90,
        ];

        // Extract the numeric value and units from the time unit string
        preg_match('/(\d+) (\w+)/', $timeUnit, $matches);

        // Numeric value
        $numericValue = intval($matches[1]);

        // Units
        $units = strtolower($matches[2]);

        // Calculate the equivalent days
        $days = $numericValue * $timeUnitToDays[$units];


        preg_match('/(\d+(?:\.\d+)?)\s*(\w+)/', $patientMed->DrugDose, $dosematches);

        // Numeric value
        $numericValueDose = ctype_digit($dosematches[1]) ? (int) $dosematches[1] : (float) $dosematches[1];

        // Units
        $unitsDose = strtolower($dosematches[2]);
        $patientData = [
            "contained" => [
                [
                    "resourceType" => "Medication",
                    "id" => $patientMed->DrugTableUUID,
                    "status" => "active",
                    "code" => [
                        "coding" => [
                            [
                                "system" => "http://www.nlm.nih.gov/research/umls/rxnorm",
                                "code" => $patientMed->DrugId,
                                "display" => $patientMed->DrugName.' '.$patientMed->DrugDose
                            ]
                        ]
                    ]
                ]
            ],
            "resourceType" => "MedicationRequest",
            "meta" => [
                "lastUpdated" => $patientMed->UpdateDate ? \Carbon\Carbon::parse($patientMed->UpdateDate)->toIso8601String() : null,
                "createdAt" => $patientMed->CreateDate ? \Carbon\Carbon::parse($patientMed->CreateDate)->toIso8601String() : null
            ],
            "identifier" => [
                [
                    "value" => $patientMed->MDTreatmentSuggestionId // Assuming you have a unique_id field in your Patient model
                ]
            ],
            "subject" => [
                "identifier" => $patientMed->PatientId // Assuming you have a PatientId field in your Patient model
            ],
            "performer" => [
                    "identifier" => $patientMed->identifier // Assuming you have a Facility_identifier field in your Patient model
    
            ],
            "medicationReference" => [
                "reference" => '#'.$patientMed->DrugTableUUID
            ],
            "dispenseRequest" => [
                "expectedSupplyDuration" => [
                    "value" => $days,
                    "unit" => 'days',
                    "system" => "http://unitsofmeasure.org",
                    "code" => 'd'
                ]
            ],
            "dosageInstruction" => [
                [
                    "timing" => [
                        "code" => "BID"
                    ],
                    "doseAndRate" => [
                        [
                            "doseQuantity" => [
                                "value" => $numericValueDose,
                                "unit" => $unitsDose,
                                "system" => "http://unitsofmeasure.org",
                                "code" => $unitsDose
                            ]
                        ]
                    ],
                    "text" => $patientMed->DrugDose.' '."BID"
                ]
            ]
];

        // Register the patient

        $registrationResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

        if (isset($registrationResponse['error'])) {
            // Handle registration error
            ApiPatientList::where('PatientId', $patientMed->PatientId)->update(['MedicineStatus' => 'error']);
            $errorResponses[] = ['error' => $registrationResponse['error']];
        } elseif ($registrationResponse['status'] == 202) {
            // Update registration status for successful registration
            ApiPatientList::where('PatientId', $patientMed->PatientId)->update(['MedicineStatus' => 'sent']);
            $successResponses[] = ['message' => 'Patient Medication sent successfully'];
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
        $unsent = ApiMedicineTrigView::where('identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }
}
