<?php

namespace App\Http\Livewire\Business\Vfms;

use App\Models\Business;
use App\Models\BusinessLocation;
use Livewire\Component;

class FetchBusinessUnitDataModal extends Component
{
    public $businessUnits;
    public $location;
    public $business;
    public function mount($id, $is_business){
        if($is_business){
            $this->business = Business::findOrFail(decrypt($id));
            $this->location = $this->business->headquarter;
        } else {
            $this->location = $this->location;
        }
    }

    public function render()
    {
        return view('livewire.business.vfms.fetch-business-unit-data-modal');
    }
}
