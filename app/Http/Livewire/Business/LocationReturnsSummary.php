<?php

namespace App\Http\Livewire\Business;

use App\Models\BusinessLocation;
use Livewire\Component;

class LocationReturnsSummary extends Component
{
    public $location, $total;

    public function mount($locationId){
        $this->location = BusinessLocation::find(decrypt($locationId));
        if (is_null($this->location)){
            abort(404, 'Location not found.');
        }
        $this->total = $this->location->taxReturns()->where('currency', 'TZS')->sum('total_amount');
        $this->totalUSD = $this->location->taxReturns()->where('currency', 'USD')->sum('total_amount');
        $this->outstanding = $this->location->taxReturns()->where('currency', 'TZS')->sum('outstanding_amount');
        $this->outstandingUSD = $this->location->taxReturns()->where('currency', 'USD')->sum('outstanding_amount');
        $this->hasUSD = $this->location->taxReturns()->where('currency', 'USD')->count();
    }

    public function render(){
        return view('livewire.business.location-returns-summary');
    }
}
