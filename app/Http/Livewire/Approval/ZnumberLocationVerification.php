<?php

namespace App\Http\Livewire\Approval;

use App\Models\TaxType;
use App\Services\Api\VfmsInternalService;
use App\Traits\Vfms\VfmsLocationTrait;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vfms\VfmsBusinessUnit;

class ZnumberLocationVerification extends Component
{
    use CustomAlert, VfmsLocationTrait;

    public $location;
    public $selectedUnit = [];
    public $units = [];
    public $business_units;
    public $response = [];
    public $fetch = false;
    public $is_requested = false;

    public function mount($location){
        $this->location = $location;
        $this->business_units = VfmsBusinessUnit::where('location_id', $this->location->id)->where('parent_id', null)->get();

        if($this->location->ward->vfms_ward && $this->location->business->headquarter->ward->vfms_ward) {
            $this->fetch = $this->location->ward->vfms_ward->locality_id != $this->location->business->headquarter->ward->vfms_ward->locality_id;
        }
    }

    public function verifyZNumber() {
        $this->units = [];
        try {
            $vfmsService = new VfmsInternalService;
            $response = $vfmsService->getBusinessUnits($this->location->business, $this->location, $this->fetch);
//            dd($response);
            $this->is_requested = true;
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
            $this->units = $this->buildBusinessUnitTree($businessUnits);

        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function complete() {
        $links = [];
        $linkData = [];
        foreach ($this->units as $value) {
            if (key_exists('is_selected', $value) && $value['is_selected']) {
                $links[] = $value['unit_id'];
                $linkData[] = $value;
            }
        }
        if(count($links) == 0) {
            $this->customAlert('warning', 'Please select units to link');
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
            if ($this->is_requested){
                foreach ($this->units as $unit) {
                    $taxtype = TaxType::select('id', 'code')->where('code', $this->mapVfmsTaxType($unit['tax_type']))->first();

                    if (!$taxtype) {
                        $this->customAlert('error', 'Missing VFMS Tax Type Mapping');
                        return;
                    }

                    $vfmsBusinessUnit = VfmsBusinessUnit::where('unit_id', $unit['unit_id'])->first();

                    if (!$vfmsBusinessUnit) {
                        $businessName = $this->location->business->name;
                        $tradingName = $this->location->business->trading_name;
                        $previousZno = $this->location->business->previous_zno;
                        VfmsBusinessUnit::create([
                            'unit_id' => $unit['unit_id'],
                            'business_id' => $this->location->business_id,
                            'unit_name' => $unit['unit_name'],
                            'business_name' => $data['business_name'] ?? $businessName,
                            'trade_name' => $data['trade_name'] ?? $tradingName ?? $businessName,
                            'locality_id' => $unit['locality_id'],
                            'vfms_tax_type' => $unit['tax_type'],
                            'crdb_tax_type_id' => $taxtype->id, // Mapped with crdb tax type id
                            'tax_office' => $unit['tax_office'] ?? null,
                            'street' => $unit['street'],
                            'znumber' => $previousZno,
                            'is_headquarter' => false,
                            'location_id' => key_exists('is_selected', $unit) && $unit['is_selected'] ? $this->location->id : false,
                            'integration' => $unit['integration']
                        ]);
                    } else {
                        $vfmsBusinessUnit->location_id = key_exists('is_selected', $unit) && $unit['is_selected'] ? $this->location->id : false;
                        $vfmsBusinessUnit->save();
                    }

                    $this->location->vfms_associated_at = Carbon::now()->toDateTimeString();
                    $this->location->save();
                }
            } else {
                foreach ($this->selectedUnit as $key => $value) {
                    if ($value) {
                        $unit = VfmsBusinessUnit::findOrFail($key);
                        $unit->location_id = $this->location->id;
                        $unit->save();
                    }
                }
            }
            DB::commit();
            $this->customAlert('success', 'VFMS Business unit(s) linked with business branch');
            return redirect()->back()->getTargetUrl();
        } catch(Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.approval.znumber-location-verification');
    }
}
