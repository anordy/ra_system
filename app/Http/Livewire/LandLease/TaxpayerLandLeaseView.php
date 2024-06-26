<?php

namespace App\Http\Livewire\LandLease;

use App\Enum\LeaseStatus;
use Livewire\Component;
use App\Models\LandLease;
use App\Models\LeasePayment;

class TaxpayerLandLeaseView extends Component
{
    public $landLease;

    //mount function
    public function mount($enc_id)
    {
        $this->landLease = LandLease::find(decrypt($enc_id));

        $statuses = [
            LeaseStatus::IN_ADVANCE_PAYMENT,
            LeaseStatus::LATE_PAYMENT,
            LeaseStatus::ON_TIME_PAYMENT,
            LeaseStatus::COMPLETE
        ];

        $this->unpaidLease = LeasePayment::where('land_lease_id', $this->landLease->id)->whereNotIn('status', $statuses)->exists();
    }

    public function render()
    {
        return view('livewire.land-lease.taxpayer-land-lease-view',);
    }
}
