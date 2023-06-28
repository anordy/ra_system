<?php

namespace App\Http\Livewire\Business\Vfms;

use App\Models\Vfms\VfmsBusinessUnit;
use Livewire\Component;

class BusinessUnitDetails extends Component
{
    public $businessUnits;
    public $location;
    public function mount($location){
        $this->location = $location;
        $this->businessUnits = VfmsBusinessUnit::where('location_id', $this->location->id)->where('parent_id', null)->get();
    }

    public function render()
    {
        return view('livewire.business.vfms.business-unit-details');
    }
}
