<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LeasePayment;
use App\Models\TaxType;
use App\Traits\PaymentsTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ViewLeasePayment extends Component
{
    use LivewireAlert, PaymentsTrait;
    public $landLease;
    public $taxType;
    public $leasePayment;

    public function mount($enc_id)
    {
        $this->leasePayment = LeasePayment::find(decrypt($enc_id));
        if(is_null($this->leasePayment)){
            abort(404);
        }
        $this->taxType = TaxType::where('code', TaxType::LAND_LEASE)->first();
    }

    public function render()
    {
        return view('livewire.land-lease.view-lease-payment');
    }

}
