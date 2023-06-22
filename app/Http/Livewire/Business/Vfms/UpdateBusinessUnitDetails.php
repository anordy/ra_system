<?php

namespace App\Http\Livewire\Business\Vfms;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Vfms\VfmsBusinessUnit;
use App\Services\Api\VfmsInternalService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdateBusinessUnitDetails extends Component
{
    public $location;
    public $business;
    public $business_units;
    public function mount($id, $is_business){
        if($is_business){
            $this->business = Business::findOrFail(decrypt($id));
            $this->location = $this->business->headquarter;
        } else {
            $this->location = BusinessLocation::findOrFail(decrypt($id));
            $this->business = $this->location->business;
        }

        $this->business_units = VfmsBusinessUnit::where('znumber', $this->business->previous_zno)
//                                ->where('locality_id', $this->location->ward->vfms_ward->locality_id)
                                ->get();
    }

    public function verifyZNumber() {
        $this->units = [];
        try {
            $vfmsService = new VfmsInternalService;
            $response = $vfmsService->getBusinessUnits($this->location->business, $this->location, false);
            $this->is_requested = true;

            if (array_key_exists('error', $response) && $response['error'] == 'validation-failed') {
                $this->customAlert('error', $response['error_info'] ?? 'Something went wrong, please contact the administrator for help');
                return;
            } else if (array_key_exists('data', $response) && $response['data']['status'] == 'successful') {
                $this->units = $response['data']['body'] ?? [];
                if (array_key_exists('statusCode', $this->units) && $this->units['statusCode'] != 200) {
                    $this->customAlert('warning', $this->units['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
                    $this->units = [];
                    return;
                } else {
                    if (count($this->units) == 0) {
                        $this->customAlert('warning', 'No data found');
                        return;
                    }

                    // Check if business unit associated to another business location
                    foreach ($this->units as $key => $item){
                        if ($this->checkIfAssociated($item)){
                            unset($this->units[$key]);
                        }
                    }
                }
            } else if (array_key_exists('statusCode', $response) && $response['statusCode'] != 200){
                $this->customAlert('warning', $response['statusMessage'] ?? 'Something went wrong, please contact the administration for help');
                return;
            }

        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function render()
    {
        return view('livewire.business.vfms.update-business-unit-details');
    }
}
