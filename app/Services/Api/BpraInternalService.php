<?php

namespace App\Services\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

class BpraInternalService
{
    use CustomAlert;

    public function getData($business){
        $shareHolders = []; $directors = []; $listShareHolderShares = [];

        $bpra_internal = config('modulesconfig.api_url') . '/bpra-internal/get_entity_full';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token) {
            $authorization = "Authorization: Bearer ". $access_token;

            $payload = [
                'registration_no' => $business->reg_no
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $bpra_internal,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                if (json_decode($response)->error_info == 200){
                    return [
                        'message' => 'unsuccessful',
                        'data' => null
                    ];
                } else {
                    Log::error('BPRA Something wrong: '.$response);
                    return [
                        'message' => 'failed',
                        'data' => null
                    ];
                }

            }

            curl_close($curl);
            $res = json_decode($response, true);


            if ($res) {
                if(array_key_exists('group_shareholder_shares', $res['data']['xml']['EApplication'])){
                    $listShareHolderShares = $res['data']['xml']['EApplication']['group_shareholder_shares']['list_shareholder_shares'];
                } 
            
                if(array_key_exists('list_entitydata', $res['data']['xml'])){
                    $listEntityData = $res['data']['xml']['list_entitydata'];

                    foreach ($listEntityData as $entityData) {

                        if ($entityData['list_type'] == 'list_directors') {
                            $directors[] = [
                                'business_id' => $business->id,
                                'country' => $entityData['country'] ?? '',
                                'birth_date' => $entityData['birth_date'] ?? '',
                                'first_name' => $entityData['first_name'] ?? '',
                                'middle_name' => $entityData['middle_name'] ?? '',
                                'last_name' => $entityData['last_name'] ?? '',
                                'gender' => $entityData['gender'] ?? '',
                                'nationality' => $entityData['nationality'] ?? '',
                                'national_id' => $entityData['national_id'] ?? '',
                                'city_name' => $entityData['city_name'] ?? '',
                                'zip_code' => $entityData['zip_code'] ?? '',
                                'first_line' => $entityData['first_line'] ?? '',
                                'second_line' => $entityData['second_line'] ?? '',
                                'third_line' => $entityData['third_line'] ?? '',
                                'email' => $entityData['email'] ?? '',
                                'mob_phone' => $entityData['mob_phone'] ?? '',
                                'created_at' => Carbon::now(),
                            ];
                        }
    
                        if ($entityData['list_type'] == 'list_shareholder' || $entityData['list_type'] == 'list_ownership') {
                            $shareHolders[] = [
                                'business_id' => $business->id,
                                'country' => $entityData['country'] ?? '',
                                'birth_date' => $entityData['birth_date'] ?? '',
                                'first_name' => $entityData['first_name'] ?? '',
                                'middle_name' => $entityData['middle_name'] ?? '',
                                'last_name' => $entityData['last_name'] ?? '',
                                'gender' => $entityData['gender'] ?? '',
                                'nationality' => $entityData['nationality'] ?? '',
                                'national_id' => $entityData['national_id'] ?? '',
                                'city_name' => $entityData['city_name'] ?? '',
                                'zip_code' => $entityData['zip_code'] ?? '',
                                'first_line' => $entityData['first_line'] ?? '',
                                'second_line' => $entityData['second_line'] ?? '',
                                'third_line' => $entityData['third_line'] ?? '',
                                'email' => $entityData['email'] ?? '',
                                'mob_phone' => $entityData['mob_phone'] ?? '',
                                'entity_name' => $entityData['entity_name'] ?? '',
                                'full_address' => $entityData['full_address'] ?? '',
                                'created_at' => Carbon::now(),
                            ];
                        }
                    }

                }
                

                $businessData = [
                    'reg_number' => $res['data']['reg_number'],
                    'business_name' => $res['data']['entity_name'],
                    'reg_date' => $res['data']['reg_date'],
                    'applicant_name' => $res['data']['xml']['EApplication']['group_applicant']['first_name'], $res['data']['xml']['EApplication']['group_applicant']['last_name'],
                    'mob_phone' => $res['data']['xml']['EApplication']['group_applicant']['mob_phone'],
                    'email' => $res['data']['xml']['EApplication']['group_applicant']['email'],
                ];

                $data = [
                    'businessData' => $businessData,
                    'shareHolders' => $shareHolders,
                    'directors' => $directors,
                    'listShareHolderShares' => $listShareHolderShares
                ];
                Log::info($data);

            } else {

                $businessData = [
                    'reg_number' => null,
                    'business_name' => null,
                    'reg_date' => null,
                    'applicant_name' => null,
                    'mob_phone' => null,
                    'email' => null,
                ];

                $data = [
                    'businessData' => $businessData,
                    'shareHolders' => $shareHolders,
                    'directors' => $directors,
                    'listShareHolderShares' => $listShareHolderShares
                ];
            }

        } else {
            Log::error('BPRA: Error On Access token Authentication from Api Server!');

            $businessData = [
                'reg_number' => null,
                'business_name' => null,
                'reg_date' => null,
                'applicant_name' => null,
                'mob_phone' => null,
                'email' => null,
            ];
        }

        return [
            'message' => 'successful',
            'data' => $data
        ];
    }

}