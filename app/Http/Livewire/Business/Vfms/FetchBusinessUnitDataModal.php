<?php

namespace App\Http\Livewire\Business\Vfms;

use App\Models\BusinessLocation;
use Livewire\Component;

class FetchBusinessUnitDataModal extends Component
{
    public $businessUnits;
    public $location;
    public function mount($id){
        $this->location = BusinessLocation::find(decrypt($id));
//        dd($this->location);
    }

    public function render()
    {
        return view('livewire.business.vfms.fetch-business-unit-data-modal');
    }
}
