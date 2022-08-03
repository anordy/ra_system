<?php

namespace App\Http\Livewire\LandLease;

use Livewire\Component;
use App\Models\LandLease;

class LandLeaseView extends Component
{
    public $landLease;


    //mount function
    public function mount($enc_id)
    {
        $this->landLease = LandLease::find(decrypt($enc_id));
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-view');
    }
}
