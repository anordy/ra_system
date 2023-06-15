<?php

namespace App\Http\Livewire\Approval;

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
    use CustomAlert;

    public $business;
    public $selectedUnitHeadquarter;
    public $response = [];

    public function mount($business){
        $this->business = $business;
    }

    public function verifyZNumber() {
        $this->response = [];
        try {

            $units = VfmsBusinessUnit::where('znumber', $this->business->previous_zno)->where('is_headquarter', '!=', true)->get();

            dd($units);
            
            $vfmsService = new VfmsInternalService;
            $response = $vfmsService->getBusinessUnits($this->business);

            if (array_key_exists('error', $response) && $response['error'] == 'validation-failed') {
                $this->customAlert('error', $response['error_info'] ?? 'Something went wrong, please contact the administrator for help');
                return;
            } else if (array_key_exists('data', $response) && $response['data']['status'] == 'successful') {
                $this->response = $response['data']['body'] ?? [];
            
                if (count($this->response) == 0) {
                    $this->customAlert('warning', 'No data found');
                    return;
                }
            } else {
                $this->customAlert('warning', 'Something went wrong, please contact the administration for help');
                return;
            }

        } catch (Exception $e) {
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function complete() {
        if (!$this->selectedUnitHeadquarter) {
            $this->customAlert('warning', 'Please select a headquarter unit');
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
                    'is_headquarter' => $unit['is_headquarter'] ?? false
                ]);
            }

            $this->business->znumber_verified_at = Carbon::now()->toDateTimeString();
            $this->business->save();

            DB::commit();

            $this->flash('success', 'Z-Number approved successfully', [], redirect()->back()->getTargetUrl());
        } catch(Exception $e) {
            DB::rollBack();
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
        
    }

    public function mapVfmsTaxType($tax_type) {
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
        } else {
            return null;
        }
    }

    public function selectHeadquarter($index) {
        $units = [];
        foreach ($this->response as $unit) {
            unset($unit['is_headquarter']);
            $units[] = $unit;
        }
        $units[$index]['is_headquarter'] = true;
        $this->selectedUnitHeadquarter = $units[$index];
        $this->response = $units;
    }

    public function render()
    {
        return view('livewire.approval.znumber-verification');
    }
}
