<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Vfms\VfmsBusinessUnit;

class ZnumberLocationVerification extends Component
{
    use CustomAlert;

    public $location;
    public $selectedUnit;
    public $units = [];
    public $business_unit;

    public function mount($location){
        $this->location = $location;
        $this->units = VfmsBusinessUnit::where('znumber', $this->location->business->previous_zno)->where('is_headquarter', '!=', true)->get();
        $this->business_unit = VfmsBusinessUnit::where('location_id', $this->location->id)->first();
    }

    public function complete() {
        if (!$this->selectedUnit) {
            $this->customAlert('warning', 'Please select a location unit for linking');
            return;
        }
        
        try {
            $this->selectedUnit->location_id = $this->location->id;
            $this->selectedUnit->save();
            $this->flash('success', 'VFMS Business unit linked with business branch', [], redirect()->back()->getTargetUrl());
        } catch(Exception $e) {
            Log::error($e);
            return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
        
    }

    public function selectUnitLocation($id) {
        $this->selectedUnit = $this->units->where('id', $id)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.approval.znumber-location-verification');
    }
}
