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
    public $selectedUnit = [];
    public $units = [];
    public $business_unit;

    public function mount($location){
        $this->location = $location;
        $this->units = VfmsBusinessUnit::where('znumber', $this->location->business->previous_zno)->where('is_headquarter', '!=', true)->get();
        $this->business_unit = VfmsBusinessUnit::select('id')->where('location_id', $this->location->id)->get();
    }

    public function complete() {
        $links = [];

        foreach ($this->selectedUnit as $key => $value) {
            if ($value) {
                $links[] = $value;
            }
        }

        if(count($links) == 0) {
            $this->customAlert('warning', 'Please select a unit to link');
            return;
        }

        DB::beginTransaction();
        try {
            foreach ($this->selectedUnit as $key => $value) {
                if ($value) {
                    $unit = VfmsBusinessUnit::findOrFail($key);
                    $unit->location_id = $this->location->id;
                    $unit->save();
                }
            }
            DB::commit();
            $this->flash('success', 'VFMS Business unit linked with business branch', [], redirect()->back()->getTargetUrl());
        } catch(Exception $e) {
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
