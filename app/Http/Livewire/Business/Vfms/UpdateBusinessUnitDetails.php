<?php

namespace App\Http\Livewire\Business\Vfms;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Vfms\VfmsBusinessUnit;
use App\Services\Api\VfmsInternalService;
use App\Traits\CustomAlert;
use App\Traits\Vfms\VfmsLocationTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class UpdateBusinessUnitDetails extends Component
{
    use CustomAlert, VfmsLocationTrait;
    public $location;
    public $business;
    public $business_units;
    public $selectedUnit = [];
    public $is_business = false;
    public function mount($id, $is_business){
        $this->is_business = $is_business;
        if($this->is_business){
            $this->business = Business::findOrFail(decrypt($id));
            $this->location = $this->business->headquarter;
        } else {
            $this->location = BusinessLocation::findOrFail(decrypt($id));
            $this->business = $this->location->business;
        }

        $this->business_units = VfmsBusinessUnit::where('znumber', $this->business->previous_zno)
                                ->where('locality_id', $this->location->ward->vfms_ward->locality_id)
                                ->where('location_id', null)
                                ->Orwhere('location_id', $this->location->id)
                                ->where('parent_id', null)
                                ->get();

        foreach ($this->business_units as $unit){
            if ($this->location->id == $unit->location_id){
                $this->selectedUnit[$unit->id] = true;
            }
        }

    }

    public function checkSelected($id){
        foreach ($this->business_units as $unit){
            if ($unit->id == $id){
                return $unit;
            }
        }
    }

    public function complete() {
        $links = [];
        foreach ($this->selectedUnit as $key => $value) {
            if ($value) {
                $links[] = $value;
            } else {
                unset($this->selectedUnit[$key]);
            }
        }

        if(count($links) == 0) {
            $this->customAlert('warning', 'Please select a unit to link');
            return;
        }

        $uniqueCombinations = collect($links)->unique(function ($item) {
            $unit = $this->checkSelected($item);
            $integration = $unit['integration'] ? 1 : 0;
            return $unit['tax_type'] . '_' . $integration;
        });

        if ($uniqueCombinations->count() !== count($links)) {
            $this->customAlert('warning', 'More than one business unit selected with same Tax Type!');
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($this->business_units as $unit){
                $selected = false;
                foreach ($this->selectedUnit as $selectedKey => $value){
                    $selected = $selectedKey == $unit->id;
                }
                $this->updateBusinessUnit($unit->id, $selected);
            }
            DB::commit();
            $this->customAlert('success', 'VFMS Business unit(s) updated successful.');
            $route = $this->is_business ? 'business.registrations.index' : 'business.branches.index';
            return redirect()->route($route);
        } catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function updateBusinessUnit($unitID, $selected){
        $this->updateUnit($unitID, $selected);
        $unitChildren = VfmsBusinessUnit::find($unitID)->getChildrenBusinessUnits($unitID);
        if(count($unitChildren)){
            foreach ($unitChildren as $child){
                $this->updateUnit($child->unit_id, $selected);
            }
        }
    }

    private function updateUnit($unitID, $selected){
        $updateUnit = VfmsBusinessUnit::find($unitID);
        $updateUnit->is_headquarter = $this->location->is_headquarter;
        $updateUnit->location_id =  $selected ? $this->location->id : null;
        $updateUnit->save();
    }

    public function render(){
        return view('livewire.business.vfms.update-business-unit-details');
    }
}
