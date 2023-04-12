<?php

namespace App\Http\Livewire\Claims;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxCredit;
use App\Traits\CustomAlert;
use Livewire\Component;

class ClaimsApproval extends Component {

    use CustomAlert;

    public $paymentType;
    public $installmentCount;
    public $claim;

    protected $rules = [
        'paymentType' => 'required|strip_tag',
        'installmentCount' => 'required_if:paymentType,installment|strip_tag'
    ];

    public function deny(){
        $this->claim->status = TaxClaimStatus::REJECTED;
        $this->claim->save();
    }

    public function approve(){
        TaxCredit::create([
            'business_id' => $this->claim->business_id,
            'location_id' => $this->claim->location_id,
            'tax_type_id' => $this->claim->tax_type_id,
            'claim_id' => $this->claim->id,
            'payment_method' => $this->paymentType,
            'amount' => $this->claim->amount,
            'currency' => $this->claim->currency,
            'installments_count' => $this->installmentCount,
            'status' => 'draft'
        ]);

        $this->claim->status = TaxClaimStatus::APPROVED;
        $this->claim->save();

        session()->flash('success', 'Claim approved.');
        return redirect()->route('claims.show', encrypt($this->claim->id));
    }

    public function render(){
        return view('livewire.claims.approval');
    }
}