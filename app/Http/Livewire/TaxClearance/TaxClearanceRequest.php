<?php

namespace App\Http\Livewire\TaxClearance;

use App\Models\Returns\Petroleum\PetroleumReturn;
use Livewire\Component;

class TaxClearanceRequest extends Component
{
    public $petroleumTotal;
    public function mount($business_location_id){
        
        $this->petroleumTotal = PetroleumReturn::where('business_location_id', $business_location_id)
        ->where('status', '!=' ,'complete')
        ->get();

        dd($this->petroleumTotal);

    }

    public function render()
    {
        return view('livewire.tax-clearance.tax-clearance-request');
    }
}
