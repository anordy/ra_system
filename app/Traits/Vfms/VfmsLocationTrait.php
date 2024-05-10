<?php
namespace App\Traits\Vfms;

use App\Enum\VfmsTaxTypeMapping;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\District;
use App\Models\Region;
use App\Models\Street;
use App\Models\Vfms\VfmsBusinessUnit;
use App\Models\VfmsWard;
use App\Models\Ward;
use App\Services\Api\ApiAuthenticationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait VfmsLocationTrait
{
    function checkRegion($data){
        if (array_key_exists('region_name', $data)){
            $region = Region::select('id', 'name')->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$data['region_name']}%"])->first();
            return $region;
        } else {
            $this->line('Region name does not exist from the response');
        }
    }


    function addRegion($data){

        $pattern = '/'.Region::PEMBA.'/';
        if (array_key_exists('region_name', $data)) {

            if (preg_match($pattern, $data['region_name'])) {
                $location = Region::PEMBA;
            } else {
                $location = Region::UNGUJA;
            }

            $region = Region::create([
                'name' => $data['region_name'],
                'location' => $location,
                'is_approved' => true,
                'is_updated' => true,
                'created_at' => Carbon::now()
            ]);

            $this->addOrCheckDistrict($region, $data);
        } else {
            $this->line('Region name does not exist from the response');
        }
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
      $ward = Ward::create([
            'name' => $data['locality_name'],
            'district_id' => $district->id,
            'is_approved' => true,
            'is_updated' => true,
            'created_at' =>Carbon::now()
        ]);

      Street::create([
          'name' => $data['locality_name'],
          'ward_id' => $ward->id,
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
            Log::channel('vfms')->info($response);
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

    function mapVfmsTaxType($tax_type) {
        if ($tax_type == 'A' || $tax_type == 'B') {
            return VfmsTaxTypeMapping::A;
        } else if ($tax_type == 'C') {
            return VfmsTaxTypeMapping::C;
        }  else if ($tax_type == 'D') {
            return VfmsTaxTypeMapping::D;
        }  else if ($tax_type == 'E') {
            return VfmsTaxTypeMapping::E;
        }  else if ($tax_type == 'F') {
            return VfmsTaxTypeMapping::F;
        }  else if ($tax_type == 'G') {
            return VfmsTaxTypeMapping::G;
        }else if ($tax_type == 'I') {
                return VfmsTaxTypeMapping::I;
        }else if ($tax_type == 'J') {
            return VfmsTaxTypeMapping::J;
        } else {
            return null;
        }
    }

    function checkIfAssociated($businessUnit){
        $checkBusinessUnit = VfmsBusinessUnit::where('unit_id', $businessUnit['unit_id'])->where('location_id', '!=', null)->exists();
        return $checkBusinessUnit;
    }

    function sendnotificationToAdmin($message){
        $payload = [
            'message' => $message,
            'user_name' => null,
            'user_type' => "staff",
            'business_name' => null,
            'phone_number' => null,
            'email' => null
        ];
        event(new SendSms('vfms-client-notification-sms', $payload));
        event(new SendMail('vfms-client-notification-mail', $payload));
    }

    function updateVfmsUnitsWithZtnLocation($request): ?array
    {
        $vfms_internal = config('modulesconfig.api_url') . '/vfms-internal/business/unit/update/znt/location';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token) {
            $authorization = "Authorization: Bearer ". $access_token;

            $payload = ['location_info' => $request];

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
            Log::channel('vfms')->info($response);
            $response = json_decode($response);
            if (isset($response->error)){
                return [
                    'message' => $response->error,
                    'code' => $statusCode
                ];
            }
            if (isset($response->statusCode)){
                return [
                    'message' => $response->statusMessage,
                    'code' => $response->statusCode
                ];
            }
            $message =  $response->message;
            curl_close($curl);
            return [
                'message' => $message,
                'code' => $statusCode
            ];

        } else {
            Log::error('VFMS: Error On Access token Authentication from Api Server!');
            return [
                'message' => "VFMS: Error On Access token Authentication from Api Server!",
                'code' => 404
            ];
        }
    }

    public function getLocationBusinessUnits($previous_zno, $location_id){
        return VfmsBusinessUnit::where('znumber', $previous_zno)
            ->where('location_id', $location_id)
            ->where('is_headquarter', true)
            ->where('parent_id', null)
            ->get();
    }

    private function buildBusinessUnitTree($businessUnits)
    {
        $tree = [];
        $indexedUnits = [];

        // Index the units by their unit_id
        foreach ($businessUnits as $businessUnit) {
            $indexedUnits[$businessUnit['unit_id']] = $businessUnit;
        }

        foreach ($businessUnits as $businessUnit) {
            if ($businessUnit['parent_id']) {
                // Check if the parent exists in the indexed array
                if (isset($indexedUnits[$businessUnit['parent_id']])) {
                    $parent = &$indexedUnits[$businessUnit['parent_id']];
                    if (!isset($parent['children'])) {
                        $parent['children'] = [];
                    }
                    $parent['children'][] = &$indexedUnits[$businessUnit['unit_id']];
                }
            } else {
                $tree[] = &$indexedUnits[$businessUnit['unit_id']];
            }
        }

        return $tree;
    }

    private function removeAssociatedBusinessUnits(){
        foreach ($this->response as $key => $item){
            if ($this->checkIfAssociated($item)){
                unset($this->response[$key]);
            }
        }
    }
}
