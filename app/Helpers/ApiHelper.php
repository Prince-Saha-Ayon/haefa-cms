<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Modules\ProductionAPI\Entities\ApiPatientTrigView;
use Modules\ProductionAPI\Entities\ApiPatientList;

class ApiHelper
{
    public static function authenticate()
    {
        $client = new Client();

        

        try {
            $response = $client->post('https://api.bd.simple.org/oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => 'haefa',
                    'client_secret' => '9a1a793ee32849de',
                    'username' => 'emailtorubel@gmail.com',
                    'password' => 'APISolutions@2024',
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            // Handle the response data as needed
            return $data;
        } catch (\Exception $e) {
            // Handle any exceptions
            return ['error' => $e->getMessage()];
        }
    }

 public static function registerPatient($accessToken, $patientData)
    {
        $client = new Client();




        try {
            $response = $client->put('https://api.bd.simple.org/api/v4/import', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => '*/*',
                    'X-Organization-ID' => 'e998b782-a38d-42e2-ba74-aeb895d6d0e9',
                    'Client-Secret' => '9a1a793ee32849de',
                ],
                'json' => $patientData,
            ]);

            // dd($response);



        $statusCode = $response->getStatusCode();

        // Decode the response body
        $responseData = json_decode($response->getBody()->getContents(), true);

        // If the status code is 202, return success along with the response data
        if ($statusCode == 202) {
            return ['status' => $statusCode, 'data' => $responseData];
        } else {
            // Handle other status codes if needed
            return ['status' => $statusCode, 'data' => $responseData];
        }



            // Handle the response data as needed

        } catch (\Exception $e) {
            // Handle any exceptions
               return ['status' => 500, 'error' => $e->getMessage()];
        }
    }
     public static function SendRegistrationPayload($accessToken, $identifier, $sending_patient)
    {
        // Retrieve patients based on the provided criteria
         $patients = ApiPatientTrigView::where('Facility_identifier', '=', $identifier)
                                  ->take($sending_patient)
                                  ->get();

    // Prepare arrays to store success and error responses
    $successResponses = [];
    $errorResponses = [];

    // Loop through the patients and format the data
    foreach ($patients as $patient) {
        $patientData = [
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

        // Register the patient
        $registrationResponse = ApiHelper::registerPatient($accessToken, ['resources' => [$patientData]]);

        if (isset($registrationResponse['error'])) {
            // Handle registration error
            ApiPatientList::where('PatientId', $patient->PatientId)->update(['RegistrationStatus' => 'error']);
            $errorResponses[] = ['error' => $registrationResponse['error']];
        } elseif ($registrationResponse['status'] == 202) {
            // Update registration status for successful registration
            ApiPatientList::where('PatientId', $patient->PatientId)->update(['RegistrationStatus' => 'sent']);
            $successResponses[] = ['message' => 'Patient registered successfully'];
        }
    }
     return [
            'successResponses' => $successResponses,
            'errorResponses' => $errorResponses
        ];
 }
}
