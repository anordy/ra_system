<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Traits\CustomAlert;
use Livewire\Component;
use App\Models\PartialPayment;

class PaymentRequestModal extends Component
{
    use CustomAlert;

    public $landLeaseId;
    public $landLease;
    public $amount;
    public $showModal = false;

    protected $rules = [
        'amount' => 'required|regex:/^[0-9,]*$/',
        'landLeaseId' => 'required|exists:land_leases,id',
    ];

    public function mount($landLease)
    {
        $this->landLease = $landLease;
    }


    public function submit()
    {
        $this->amount = str_ireplace(',', '', $this->amount);
        $this->validate();

        $landLease = LandLease::findOrFail($this->landLeaseId);

        if ($this->amount > $landLease->outstanding_amount || $this->amount <= 0) {
            $this->customAlert('warning', 'The entered amount should not be greater than the outstanding amount');
            return;
        }

        $partialPayment = PartialPayment::query()->create([
            'payment_id' => $landLease->id,
            'payment_type' => get_class($landLease),
            'amount' => $this->amount,
        ]);

        $this->reset(['amount', 'showModal', 'landLeaseId']);
        return redirect(route("tax-investigation-payments.show", encrypt($partialPayment->id)))->with('success', 'Assesment payment request submitted successfully.');
    }

    public function render()
    {
        return view('livewire.land-lease.payment-request-modal');
    }
}
