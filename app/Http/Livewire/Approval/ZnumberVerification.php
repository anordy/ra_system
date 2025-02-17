<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Traits\Vfms\VfmsLocationTrait;
use Exception;
use App\Models\TaxType;
use Livewire\Component;
use App\Traits\CustomAlert;
use App\Enum\VfmsTaxTypeMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vfms\VfmsBusinessUnit;
use App\Services\Api\VfmsInternalService;
use Carbon\Carbon;

class ZnumberVerification extends Component
{
    use CustomAlert, VfmsLocationTrait;

    public $business;
    public $response = [];
    public $errorMessage = 'Something went wrong, please contact the administrator for help';

    public function mount($business){
        $this->business = $business;
        $this->response = $this->getLocationBusinessUnits($this->business->previous_zno, $this->business->headquarter->id);
    }

    public function verifyZNumber() {
        $this->response = [];
        try {

            $vfmsService = new VfmsInternalService;
            $response = $vfmsService->getBusinessUnits($this->business, null, true);

            if (isset($response['statusCode'])){
                $this->customAlert('warning', $response['statusMessage']);
                return;
            }

            $this->response = $response['business_units'];
            if (count($this->response) == 0) {
                $this->customAlert('warning', 'No data found');
                return;
            }

            // Check if business unit associated to another business location
            $this->removeAssociatedBusinessUnits();
            $businessUnits = collect($this->response)->keyBy('unit_id');
            $this->response = $this->buildBusinessUnitTree($businessUnits);

//            if (array_key_exists('error', $response) && $response['error'] == 'validation-failed') {
//                $this->customAlert('error', $response['error_info'] ?? 'Something went wrong, please contact the administrator for help');
//                return;
//            } else if (array_key_exists('data', $response) && $response['data']['status'] == 'successful') {
//                $this->response = $response['data']['body'] ?? [];
//                if (array_key_exists('statusCode', $this->response) && $this->response['statusCode'] != 200) {
//                    $this->customAlert('warning', $this->response['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
//                    $this->response = [];
//                    return;
//                } else {
//                    if (count($this->response) == 0) {
//                        $this->customAlert('warning', 'No data found');
//                        return;
//                    }
//                    // Check if business unit associated to another business location
//                    $this->removeAssociatedBusinessUnits();
//                    $businessUnits = collect($this->response)->keyBy('unit_id');
//                    $this->response = $this->buildBusinessUnitTree($businessUnits);
//                }
//            } else if (array_key_exists('statusCode', $response) && $response['statusCode'] != 200){
//                $this->customAlert('warning', $response['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
//                return;
//            }

        } catch (Exception $e) {
            Log::error($e);
            return $this->customAlert('error', $this->errorMessage);
        }
    }


//    private function buildBusinessUnitTree($businessUnits, $parentId = null){
//        $tree = [];
//        foreach ($businessUnits as $businessUnit) {
//            if ($businessUnit['parent_id'] && $businessUnit['parent_id'] === $parentId) {
//                // Check if there are any children for this parent
//                $children = $this->buildBusinessUnitTree($businessUnits, $businessUnit['unit_id']);
//
//                // Only add the 'children' key if there are children for this parent
//                if (!empty($children)) {
//                    $businessUnit['children'] = $children;
//                }
//            } else {
//                $businessUnit['children'] = [];
//            }
//            $tree[] = $businessUnit;
//        }
//        return $tree;
//    }



    public function complete() {
        $headquarters = [];
        $linkData = [];

        foreach ($this->response as $value) {
            if (key_exists('is_headquarter', $value) && $value['is_headquarter']) {
                $headquarters[] = $value['unit_id'];
                $linkData[] = $value;
            }
        }

        if(count($headquarters) == 0) {
            $this->customAlert('warning', 'Please select a headquarter units');
            return;
        }

        $uniqueCombinations = collect($linkData)->unique(function ($item) {
            $integration = $item['integration'] ? 1 : 0;
            return $item['tax_type'] . '_' . $integration;
        });

        if ($uniqueCombinations->count() !== count($linkData)) {
            $this->customAlert('warning', 'More than one business unit selected with same Tax Type!');
            return;
        }


        DB::beginTransaction();
        try {
            foreach ($this->response as $unit) {
                $taxtype = TaxType::select('id', 'code')->where('code', $this->mapVfmsTaxType($unit['tax_type']))->first();

                if (!$taxtype) {
                    $this->customAlert('error', 'Missing VFMS Tax Type Mapping');
                    return;
                }
                $vfmsBusinessUnit = VfmsBusinessUnit::where('unit_id', $unit['unit_id'])->first();
                if (!$vfmsBusinessUnit) {
                    $this->createBusinessUnit($unit, $taxtype);
                } else {
                    $vfmsBusinessUnit->location_id = !empty($unit['is_headquarter']) ? $this->business->headquarter->id : null;
                    $vfmsBusinessUnit->is_headquarter = !empty($unit['is_headquarter']) ? $unit['is_headquarter'] : false;
                    $vfmsBusinessUnit->save();
                }
            }

            $this->business->znumber_verified_at = Carbon::now()->toDateTimeString();
            $this->business->save();

            $headquarters_location = BusinessLocation::find($this->business->headquarter->id);
            $headquarters_location->vfms_associated_at = Carbon::now()->toDateTimeString();
            $headquarters_location->save();

            DB::commit();

            $this->response = $this->getLocationBusinessUnits($this->business->previous_zno, $this->business->headquarter->id);
            $this->customAlert('success', 'Z-Number approved successfully.');
            return redirect()->back();
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', $this->errorMessage);
        }
        
    }

    public function createBusinessUnit($data, $taxtype){
        $this->createData($data, $taxtype);
        if (isset($data['children'])){
            if(count($data['children'])){
                foreach ($data['children'] as $child){
                    $this->createData($child, $taxtype);
                }
            }
        }
    }

    private function createData($data, $taxtype){
        $businessName = $this->business->name;
        $tradingName = $this->business->trading_name;
        $previousZno = $this->business->previous_zno;
        VfmsBusinessUnit::create([
            'unit_id' => $data['unit_id'],
            'business_id' => $this->business->id,
            'unit_name' => $data['unit_name'],
            'business_name' => $data['business_name'] ?? $businessName,
            'trade_name' => $data['trade_name'] ?? $tradingName ?? $businessName,
            'parent_id' => $data['parent_id'],
            'locality_id' => $data['locality_id'],
            'vfms_tax_type' => $data['tax_type'],
            'crdb_tax_type_id' => $taxtype->id, // Mapped with crdb tax type id
            'tax_office' => $data['tax_office'] ?? null,
            'street' => $data['street'],
            'znumber' => $previousZno,
            'is_headquarter' => (key_exists('is_headquarter', $data) && $data['is_headquarter']) || $data['parent_id'] ?? false,
            'location_id' => (key_exists('is_headquarter', $data) && $data['is_headquarter']) || $data['parent_id'] ? $this->business->headquarter->id : null,
            'integration' => $data['integration']
        ]);
    }

    public function returnForCorrection(){
        DB::beginTransaction();
        try {
            $updateBusiness = Business::find($this->business->id);
            $updateBusiness->valid_z_number = false;
            $updateBusiness->save();

            DB::commit();

            $payload = [
                'message' => "You are kindly requested to change Z-Number for your business ". $this->business->name ." as the previous one is incorrect.",
                'taxpayer_name' => $this->business->responsiblePerson->first_name,
                'business_name' => $this->business->name,
                'user_type' => "taxpayer",
                'phone_number' => $this->business->responsiblePerson->phone_number,
                'email' => $this->business->responsiblePerson->email
            ];

            event(new SendSms('vfms-client-notification-sms', $payload));
            event(new SendMail('vfms-client-notification-mail', $payload));

            $this->customAlert('success', 'Requesting taxpayer to correct Z-Number successful.');
            return redirect()->route('business.registrations.index');
        } catch (Exception $e){
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', $this->errorMessage);
        }
    }

    protected $listeners = [
        'returnForCorrection'
    ];

    public function confirmPopUpModal($action)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null
        ]);
    }

    public function render()
    {
        return view('livewire.approval.znumber-verification');
    }
}
