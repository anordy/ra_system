<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Log;

class SurveySolutionInternalService
{

    public function getPropertyInformation(string $identifierType, string $identifierNumber) {

        if (config('app.env') == 'local'){
            return  [
                "totalItems" => 4,
                "propertyInforList" => [
                    [
                        "interview__id" => "ae9b963f-0495-409a-ab34-d1b349fee59b",
                        "location" => [
                            "Accuracy" => 7.4587287902832,
                            "Altitude" => -15.357757568359,
                            "Latitude" => -5.99420464,
                            "Longitude" => 39.37934131,
                            "Timestamp" => "2023-10-31T07:26:26.907+00:00"
                        ],
                        "owner" => [
                            "zra_ref_no" => "0",
                            "zra_number" => null,
                            "fullName" => "ALEX CLAUD MLANDALI",
                            "ownership_type" => "residential_storey",
                            "mail_address" => "0",
                            "phone_no" => "0787-121-659",
                            "email_address" => null,
                            "tin" => 0,
                            "nida" => null,
                            "zanID" => $identifierNumber,
                            "passport" => "0"
                        ],
                        "agent" => [
                            "name_of_person" => null,
                            "name_of_company" => null,
                            "phone_no_1" => null,
                            "phone_no_2" => null,
                            "email" => null
                        ],
                        "valuation" => [
                            "property_id" => 1,
                            "property_feature" => "Jengo limekamilika"
                        ],
                        "region" => "Kaskazini Unguja",
                        "district" => "Kaskazini B",
                        "locality" => "Kiwengwa",
                        "property_address" => "KUMBA UREMBO",
                        "meter_no" => "54184500012",
                        "house_number" => "0",
                        "postcode" => "27",
                        "road_name" => "KIWENGWA",
                        "property_type" => "residential_storey",
                        "number_of_storey" => "3",
                        "type_of_business" => null,
                        "hotel_star" => null,
                        "property_feature" => null
                    ],
                    [
                        "interview__id" => "d82f1b4b-f19a-4f47-9de8-00aa7a2ff4aa",
                        "location" => [
                            "Accuracy" => 8.923415184021,
                            "Altitude" => -12.56079864502,
                            "Latitude" => -6.0123456,
                            "Longitude" => 39.4209587,
                            "Timestamp" => "2023-11-01T09:45:38.212+00:00"
                        ],
                        "owner" => [
                            "zra_ref_no" => "1",
                            "zra_number" => null,
                            "fullName" => "EMILY JAMESON",
                            "ownership_type" => "residential_storey",
                            "mail_address" => "1",
                            "phone_no" => "0787-121-659",
                            "email_address" => null,
                            "tin" => 1,
                            "nida" => null,
                            "zanID" => "540245407",
                            "passport" => "1"
                        ],
                        "agent" => [
                            "name_of_person" => null,
                            "name_of_company" => null,
                            "phone_no_1" => null,
                            "phone_no_2" => null,
                            "email" => null
                        ],
                        "valuation" => [
                            "property_id" => 2,
                            "property_feature" => "Jengo linajengwa"
                        ],
                        "region" => "Kusini Unguja",
                        "district" => "Kusini A",
                        "locality" => "Paje",
                        "property_address" => "KUMBA UREMBO 2",
                        "meter_no" => "54184500013",
                        "house_number" => "1",
                        "postcode" => "28",
                        "road_name" => "PAJE",
                        "property_type" => "storey_business",
                        "number_of_storey" => "4",
                        "type_of_business" => null,
                        "hotel_star" => null,
                        "property_feature" => null
                    ],
                    [
                        "interview__id" => "ae9b963f-0495-409a-ab34-d1b349fee59b",
                        "location" => [
                            "Accuracy" => 7.4587287902832,
                            "Altitude" => -15.357757568359,
                            "Latitude" => -5.99420464,
                            "Longitude" => 39.37934131,
                            "Timestamp" => "2023-10-31T07:26:26.907+00:00"
                        ],
                        "owner" => [
                            "zra_ref_no" => "0",
                            "zra_number" => null,
                            "fullName" => "ALEX CLAUD MLANDALI",
                            "ownership_type" => "residential_storey",
                            "mail_address" => "0",
                            "phone_no" => "0787-121-659",
                            "email_address" => null,
                            "tin" => 0,
                            "nida" => null,
                            "zanID" => "540245406",
                            "passport" => "0"
                        ],
                        "agent" => [
                            "name_of_person" => null,
                            "name_of_company" => null,
                            "phone_no_1" => null,
                            "phone_no_2" => null,
                            "email" => null
                        ],
                        "valuation" => [
                            "property_id" => 1,
                            "property_feature" => "Jengo limekamilika"
                        ],
                        "region" => "Kaskazini Unguja",
                        "district" => "Kaskazini B",
                        "locality" => "Kiwengwa",
                        "property_address" => "KUMBA UREMBO",
                        "meter_no" => "54184500012",
                        "house_number" => "0",
                        "postcode" => "27",
                        "road_name" => "KIWENGWA",
                        "property_type" => "residential_storey",
                        "number_of_storey" => "3",
                        "type_of_business" => null,
                        "hotel_star" => null,
                        "property_feature" => null
                    ],
                    [
                        "interview__id" => "a2c4f5e6-7b8d-9c0a-b1c2-d3e4f5a6b7c8",
                        "location" => [
                            "Accuracy" => 8.1234567890123,
                            "Altitude" => -20.123456789012,
                            "Latitude" => -10.123456789012,
                            "Longitude" => 35.123456789012,
                            "Timestamp" => "2023-11-01T08:27:27.908+00:00"
                        ],
                        "owner" => [
                            "zra_ref_no" => "1",
                            "zra_number" => null,
                            "fullName" => "John Doe",
                            "ownership_type" => "Owner",
                            "mail_address" => "Some Address",
                            "phone_no" => "0787-121-659",
                            "email_address" => "john@example.com",
                            "tin" => 123456789,
                            "nida" => null,
                            "zanID" => null,
                            "passport" => null
                        ],
                        "agent" => [
                            "name_of_person" => "Agent Smith",
                            "name_of_company" => "ABC Realty",
                            "phone_no_1" => "987-654-3210",
                            "phone_no_2" => null,
                            "email" => "agent@abcrealty.com"
                        ],
                        "valuation" => [
                            "property_id" => 2,
                            "property_feature" => "Completed Building"
                        ],
                        "region" => "South District",
                        "district" => "Some District",
                        "locality" => "Some Locality",
                        "property_address" => "123 Main Street",
                        "meter_no" => "9876543210",
                        "house_number" => "123",
                        "postcode" => "12345",
                        "road_name" => "Main Street",
                        "property_type" => "residential_storey",
                        "number_of_storey" => "2",
                        "type_of_business" => "Retail",
                        "hotel_star" => null,
                        "property_feature" => null
                    ],
                    [
                        "interview__id" => "b3d4e5f6-8c9a-b0c1-d2e3-f4a5b6c7d8e9",
                        "location" => [
                            "Accuracy" => 9.8765432109877,
                            "Altitude" => -25.987654321099,
                            "Latitude" => -15.987654321099,
                            "Longitude" => 30.987654321099,
                            "Timestamp" => "2023-11-02T09:28:28.909+00:00"
                        ],
                        "owner" => [
                            "zra_ref_no" => "2",
                            "zra_number" => null,
                            "fullName" => "Jane Smith",
                            "ownership_type" => "Owner",
                            "mail_address" => "Another Address",
                            "phone_no" => "987-654-3210",
                            "email_address" => "jane@example.com",
                            "tin" => 987654321,
                            "nida" => null,
                            "zanID" => null,
                            "passport" => null
                        ],
                        "agent" => [
                            "name_of_person" => "Agent Johnson",
                            "name_of_company" => "XYZ Realty",
                            "phone_no_1" => "123-456-7890",
                            "phone_no_2" => null,
                            "email" => "agent@xyzrealty.com"
                        ],
                        "valuation" => [
                            "property_id" => 3,
                            "property_feature" => "Vacant Land"
                        ],
                        "region" => "North District",
                        "district" => "Another District",
                        "locality" => "Another Locality",
                        "property_address" => "456 Oak Street",
                        "meter_no" => "1234567890",
                        "house_number" => "456",
                        "postcode" => "54321",
                        "road_name" => "Oak Street",
                        "property_type" => "condominium",
                        "number_of_storey" => "1",
                        "type_of_business" => null,
                        "hotel_star" => null,
                        "property_feature" => null
                    ]
                ],
                "totalPages" => 1,
                "currentPage" => 0
            ];
        }
        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $url = config('modulesconfig.api_url') . '/property-tax/property-info';

            $payload = [
                $identifierType => $identifierNumber,
            ];
            Log::info('------SEARCHING FOR IDENTIFIER------');
            Log::info(json_encode($payload));

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    "authorization: $authorization"
                ),
            ));

            $response = curl_exec($curl);

            Log::info($response);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                Log::error('FAILED');
                return [
                    'message' => $response['data']['msg'],
                    'data' => null
                ];
            } else {
                curl_close($curl);
                return json_decode($response, true);
            }

        } else {
            Log::error('FAILED TO AUTHENTICATE');
            return [
                'message' => 'failed',
                'data' => null
            ];
        }
    }

}