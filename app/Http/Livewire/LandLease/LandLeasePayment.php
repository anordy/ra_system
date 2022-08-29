<?php

namespace App\Http\Livewire\LandLease;

use App\Models\TaxType;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class LandLeasePayment extends Component
{
    use LivewireAlert, PenaltyTrait, PaymentsTrait;

    public $landLease;

    public function mount($landLease){
        $this->landLease = $landLease;
    }

    public function refresh(){
        $landLease = get_class($this->landLease)::find($this->landLease->id);
        if (!$landLease){
            session()->flash('error', 'Land Lease not found.');
            return redirect()->route('land-lease.taxpayer.list');
        }
        $this->landLease = $landLease;
    }

    public function regenerate(){
        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }
        $response = $this->regenerateControlNo($this->landLease->bill());
        if ($response){
            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            return redirect()->back()->getTargetUrl();
        }
        $this->alert('error', 'Control number could not be generated, please try again later.');
    }

    public function generate()
    {
        if(!Gate::allows('land-lease-generate-control-number')){
            abort(403);
        }
        $billItems[] = [
            'billable_id' => $this->landLease->id,
            'billable_type' => get_class($this->landLease),
            'use_item_ref_on_pay' => 'N',
            'amount' => $this->landLease->payment_amount,
            'currency' => 'USD',
            'gfs_code' => TaxType::where('code','land-lease')->first()->gfs_code,
            'tax_type_id' => TaxType::where('code','land-lease')->first()->id
        ];
        $response = $this->landLeaseGenerateControlNo($this->landLease, $billItems);
        $this->refresh();
    }

    public function render(){
        return view('livewire.land-lease.land-lease-payment');
    }
}