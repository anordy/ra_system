<?php

namespace App\Http\Livewire\Approval;

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
    public $selectedUnitHeadquarter;
    public $response = [];

    public function mount($business){
        $this->business = $business;
        $this->response = VfmsBusinessUnit::where('znumber', $this->business->previous_zno)
            ->where('locality_id', $this->business->headquarter->ward->vfms_ward->locality_id)
            ->where('location_id', $this->business->headquarter->id)
            ->where('is_headquarter', true)
            ->get();
    }

    public function verifyZNumber() {
        $this->response = [];
        try {

            $vfmsService = new VfmsInternalService;
            $response = $vfmsService->getBusinessUnits($this->business, null, true);


            if (array_key_exists('error', $response) && $response['error'] == 'validation-failed') {
                $this->customAlert('error', $response['error_info'] ?? 'Something went wrong, please contact the administrator for help');
                return;
            } else if (array_key_exists('data', $response) && $response['data']['status'] == 'successful') {
                $this->response = $response['data']['body'] ?? [];
                if (array_key_exists('statusCode', $this->response) && $this->response['statusCode'] != 200) {
                    $this->customAlert('warning', $this->response['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
                    $this->response = [];
                    return;
                } else {
                    if (count($this->response) == 0) {
                        $this->customAlert('warning', 'No data found');
                        return;
                    }
                    // Check if business unit associated to another business location
                    foreach ($this->response as $key => $item){
                        if ($this->checkIfAssociated($item)){
                            unset($$this->response[$key]);
                        }
                    }
                }
            } else if (array_key_exists('statusCode', $response) && $response['statusCode'] != 200){
                $this->customAlert('warning', $response['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
                return;
            }

        } catch (Exception $e) {
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function complete() {
        $headquarters = [];
        $linkData = [];

        foreach ($this->response as $value) {
            if (key_exists('is_headquarter', $value) && $value['is_headquarter']) {
                $headquarters[] = $value['unit_id'];
                $linkData[] = $value;
            }
        }
//        dd($linkData);
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
                    VfmsBusinessUnit::create([
                        'unit_id' => $unit['unit_id'],
                        'business_id' => $this->business->id,
                        'unit_name' => $unit['unit_name'],
                        'business_name' => $unit['business_name'] ?? null,
                        'trade_name' => $unit['trade_name'] ?? null,
                        'locality_id' => $unit['locality_id'],
                        'vfms_tax_type' => $unit['tax_type'],
                        'zidras_tax_type_id' => $taxtype->id, // Mapped with zidras tax type id
                        'tax_office' => $unit['tax_office'] ?? null,
                        'street' => $unit['street'],
                        'znumber' => $unit['znumber'],
                        'is_headquarter' => $unit['is_headquarter'] ?? false,
                        'location_id' => $unit['is_headquarter'] ? $this->business->headquarter->id : null,
                        'integration' => $unit['integration']
                    ]);
                } else {
                    $vfmsBusinessUnit->location_id = $unit['is_headquarter'] ? $this->business->headquarter->id : null;
                    $vfmsBusinessUnit->is_headquater = $unit['is_headquarter'] ?? false;
                    $vfmsBusinessUnit->save();
                }
            }

            $this->business->znumber_verified_at = Carbon::now()->toDateTimeString();
            $this->business->save();

            $headquarters_location = BusinessLocation::find($this->business->headquarter->id);
            $headquarters_location->vfms_associated_at = Carbon::now()->toDateTimeString();
            $headquarters_location->save();

            DB::commit();

            $this->flash('success', 'Z-Number approved successfully', [], redirect()->back()->getTargetUrl());
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
        
    }

    public function render()
    {
        return view('livewire.approval.znumber-verification');
    }
}
