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

class LandLeasePayment extends Component
{
    use CustomAlert, PenaltyTrait, PaymentsTrait, GepgResponse;

    public $landLease;
    public $leasePayment;

    public function mount($leasePayment){
        $this->leasePayment = $leasePayment;
        $this->landLease = $leasePayment->landLease;
    }

    public function refresh(){
        $landLease = get_class($this->landLease)::find($this->landLease->id);
        if (is_null($landLease)){
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
        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }
        $response = $this->regenerateControlNo($this->leasePayment->bill());
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            $this->leasePayment = get_class($this->leasePayment)::find($this->leasePayment->id);
            if(is_null($this->leasePayment)){
                abort(404);
            }
        } else {
            $this->customAlert('error', 'Control number could not be generated, please try again later.');
        }
    }

    public function generate()
    {
        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }
        $taxType = TaxType::where('code',TaxType::LAND_LEASE)->firstOrFail();
        $billItems[] = [
            'billable_id' => $this->leasePayment->id,
            'billable_type' => get_class($this->leasePayment),
            'use_item_ref_on_pay' => 'N',
            'amount' => $this->leasePayment->total_amount_with_penalties,
            'currency' => 'USD',
            'gfs_code' => $taxType->gfs_code,
            'tax_type_id' => $taxType->id
        ];

        $this->landLeaseGenerateControlNo($this->leasePayment, $billItems);
        $this->refresh();
    }

    public function render(){
        return view('livewire.land-lease.land-lease-payment');
    }
}