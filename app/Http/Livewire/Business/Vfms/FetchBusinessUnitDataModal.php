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
    public $is_business;
    public function mount($id, $is_business){
        $this->is_business = $is_business;
        if($this->is_business){
            $this->business = Business::findOrFail(decrypt($id));
            $this->location = $this->business->headquarter;
        } else {
            $this->location = BusinessLocation::findOrFail(decrypt($id));
        }
    }

    public function render()
    {
        return view('livewire.business.vfms.fetch-business-unit-data-modal');
    }
}
