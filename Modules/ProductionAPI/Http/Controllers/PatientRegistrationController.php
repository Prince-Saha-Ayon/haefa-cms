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
            $facilities=RefApiFacility::get();
            

            $this->setPageData('Patient Registartion API','Patient Registartion API','fas fa-th-list');
            return view('productionapi::patient-registration',compact('facilities'));
        
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
          $patients = ApiPatientTrigView::where('Facility_identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();
       

              

        // Prepare patient data array
        $patientData = [];

        // Loop through the patients and format the data
    foreach ($patients as $patient) {
        $patientData[] = [
            "resourceType" => "Patient",
            "meta" => [
                "lastUpdated" => $patient->UpdateDate ? \Carbon\Carbon::parse($patient->UpdateDate)->toIso8601String() : null,
                "createdAt" => $patient->CreateDate ? \Carbon\Carbon::parse($patient->CreateDate)->toIso8601String() : null
            ],
            "identifier" => [
                [
                    "value" => $patient->PatientId // Assuming you have a unique_id field in your Patient model
                ]
            ],
            "gender" => strtolower($patient->GenderCode),
            "birthDate" => $patient->BirthDate ? \Carbon\Carbon::parse($patient->BirthDate)->toIso8601String() : null,
            "managingOrganization" => [
                [
                    "value" => $patient->Facility_identifier // Assuming you have an organization_name field in your Patient model
                ]
            ],
            "registrationOrganization" => [
                [
                    "value" => $patient->Facility_identifier
                ]
            ],
            "deceasedBoolean" => false,
            "telecom" => [],
            "address" => [
                [
                    "line" => null,
                    "district" => $patient->Address,
                    "city" => $patient->Address,
                    "postalCode" => $patient->PostCode ?? null
                ]
            ],
            "name" => [
                "text" => $patient->Name
            ],
            "active" => true
        ];
      ApiPatientList::where('PatientId', $patient->PatientId)
            ->update(['RegistrationStatus' => 'sent']);
             
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
        $unsent = ApiPatientTrigView::where('Facility_identifier',$identifier)->count();

        return response()->json([
        'unsent'=>$unsent
    ]);


    }

   


}

