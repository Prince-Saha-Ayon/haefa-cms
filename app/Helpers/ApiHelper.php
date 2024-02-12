<?php

namespace App\Helpers;

use GuzzleHttp\Client;

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
}
