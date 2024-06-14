<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LeasePayment;
use App\Models\TaxType;
use App\Services\ZanMalipo\GepgResponse;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use App\Traits\CustomAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class LandLeasePartialPayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $leasePartialPayment;
    public $landLease;

    public function mount($partialPayment){
        $this->leasePartialPayment = $partialPayment;
        $this->landLease = $partialPayment->landLease;
    }

    public function refresh(){
        $landLease = get_class($this->landLease)::find($this->landLease->id);
        if (!$landLease){
            session()->flash('error', 'Land Lease not found.');
            return redirect()->route('land-lease.taxpayer.list');
        }
        $this->landLease = $landLease;
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }

    public function regenerate(){
        $response = $this->regenerateControlNo($this->leasePayment->bill());
        if ($response){
            session()->flash('success', __('Your request was submitted, you will receive your payment information shortly.'));
            $this->leasePayment = get_class($this->leasePayment)::find($this->leasePayment->id);
        } else {
            $this->customAlert('error', __('Control number could not be generated, please try again later.'));
        }
    }

    public function generate()
    {
        $billItems[] = [
            'billable_id' => $this->leasePayment->id,
            'billable_type' => get_class($this->leasePayment),
            'use_item_ref_on_pay' => 'N',
            'amount' => $this->leasePayment->total_amount_with_penalties,
            'currency' => 'USD',
            'gfs_code' => TaxType::where('code','land-lease')->first()->gfs_code,
            'tax_type_id' => TaxType::where('code','land-lease')->first()->id
        ];

        $this->landLeaseGenerateControlNo($this->leasePayment, $billItems);
        $this->refresh();
    }

    public function render(){
        return view('livewire.land-lease.land-lease-partial-payment');
    }
}