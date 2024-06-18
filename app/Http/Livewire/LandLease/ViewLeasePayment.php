<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LeasePayment;
use App\Models\PartialPayment;
use App\Traits\PaymentsTrait;
use App\Traits\CustomAlert;
use Livewire\Component;

class ViewLeasePayment extends Component
{
    use CustomAlert, PaymentsTrait;
    public $landLease;
    public $leasePayment;
    public $partialPayment;

    public function mount($enc_id)
    {
        $this->leasePayment = LeasePayment::find(decrypt($enc_id));
        if(is_null($this->leasePayment)){
            abort(404);
        }

        //check if it exists in partial payments
        $this->pendingPartialPayment = $this->getPendingPartialPayment();
        $this->pendingPartialPaymentStatus = $this->getPartialPaymentStatus();
        $this->partialPayment = PartialPayment::where('payment_id', $this->leasePayment->land_lease_id)->latest()->first();
    }

    public function render()
    {
        return view('livewire.land-lease.view-lease-payment');
    }

    public function getPendingPartialPayment()
    {
        return PartialPayment::where('payment_id', $this->leasePayment->land_lease_id)
            ->where('status', "pending")
            ->exists();
    }

    public function getPartialPaymentStatus()
    {
        return PartialPayment::where('payment_id', $this->leasePayment->land_lease_id)
            ->where('payment_status', "pending")
            ->first();
    }
}
