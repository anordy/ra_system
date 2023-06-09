<?php
namespace App\Traits\Vfms;

use App\Models\District;
use App\Models\Region;
use App\Models\VfmsWard;
use App\Models\Ward;
use App\Services\Api\ApiAuthenticationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait VfmsLocationTrait
{
    function checkRegion($data){
        $region = Region::select('id', 'name')->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$data['region_name']}%"])->first();
        return $region;
    }


    function addRegion($data){

        $pattern = '/'.Region::PEMBA.'/';
        if(preg_match($pattern, $data['region_name'])){
            $location = Region::PEMBA;
        } else {
            $location = Region::UNGUJA;
        }

        $region = Region::create([
            'name' => $data['region_name'],
            'location' => $location,
            'is_approved' => true,
            'is_updated' => true,
            'created_at' =>Carbon::now()
        ]);

        $this->addOrCheckDistrict($region, $data);
    }

    function addOrCheckDistrict($region, $data){
        $district = $this->checkDistrict($region, $data);
        if ($district){
            $this->addWard($district, $data);
        } else {
            $this->addDistrict($region, $data);
        }
    }

    function addDistrict($region, $data){
        $district = District::create([
            'name' => $data['district_name'],
            'region_id' => $region->id,
            'is_approved' => true,
            'is_updated' => true,
            'created_at' =>Carbon::now()
        ]);

        $this->addWard($district, $data);
    }

    function checkDistrict($region, $data){
        $district = District::select('id', 'name')
            ->where('region_id', $region->id)
            ->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$data['district_name']}%"])->first();
        return $district;
    }

    function addWard($district, $data){
        Ward::create([
            'name' => $data['locality_name'],
            'district_id' => $district->id,
            'is_approved' => true,
            'is_updated' => true,
            'created_at' =>Carbon::now()
        ]);
    }

    private function addVfmsWardToZidras($ward, $data){
        VfmsWard::updateOrCreate([
            'ward_id' => $ward->id,
            'locality_id' => $data->locality_id
        ],[
            'ward_id' => $ward->id,
            'locality_id' => $data->locality_id,
            'locality_name' => $ward->name,
        ]);
    }

    function vfmsCheck($locality_id){
        $vfms_internal = config('modulesconfig.api_url') . '/vfms-internal/get_locality';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token) {
            $authorization = "Authorization: Bearer ". $access_token;

            $payload = [
                'locality_id' => $locality_id
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $vfms_internal,
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
            $message = $statusCode != 200 ? 'Something went wrong' : 'Data Retrieved Successfully';
            curl_close($curl);
            return [
                'data' => $response,
                'msg' => $message,
                'code' => $statusCode
            ];

        } else {
            Log::error('VFMS: Error On Access token Authentication from Api Server!');
            return null;
        }
    }

    function addWardToVfms($request){
        $vfms_internal = config('modulesconfig.api_url') . '/vfms-internal/add_locality';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token) {
            $authorization = "Authorization: Bearer ". $access_token;

            $payload = [
                'district_name' => $request['district_name'],
                'locality_name' => $request['locality_name'],
                'region_name' => $request['region_name'],
            ];

            $curl = curl_init();
            Log::info('VFMS: Post ward to Vfms Start!');
            curl_setopt_array($curl, array(
                CURLOPT_URL => $vfms_internal,
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
            Log::info('VFMS: Post ward to Vfms End!');
            $message = $statusCode != 200 ? 'Something went wrong' : 'Data Retrieved Successfully';
            curl_close($curl);
            return [
                'data' => $response,
                'msg' => $message,
                'code' => $statusCode
            ];

        } else {
            Log::error('VFMS: Error On Access token Authentication from Api Server!');
            return null;
        }
    }
}
