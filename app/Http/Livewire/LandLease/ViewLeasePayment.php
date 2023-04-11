<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LeasePayment;
use App\Traits\PaymentsTrait;
use App\Traits\CustomAlert;
use Livewire\Component;

class ViewLeasePayment extends Component
{
    use CustomAlert, PaymentsTrait;
    public $landLease;
    public $leasePayment;

    public function mount($enc_id)
    {
        $this->leasePayment = LeasePayment::find(decrypt($enc_id));
        if(is_null($this->leasePayment)){
            abort(404);
        }
        
    }

    public function render()
    {
        return view('livewire.land-lease.view-lease-payment');
    }

}
