<?php

namespace App\Http\Livewire\Kyc;

use App\Models\KycAmendmentRequest;
use App\Models\User;
use Livewire\Component;

class KycAmendmentRequestShow extends Component
{
    public $amendmentRequest;
    private $old_values;
    public $taxpayer_id;
    private $new_values;
    public $createdBy;

    public function mount($id){
        $this->amendmentRequest = KycAmendmentRequest::findOrFail(decrypt($id));
        $this->createdBy = User::findorFail($this->amendmentRequest->created_by)->fullname();
        $this->taxpayer_id = $this->amendmentRequest->taxpayer_id;
        $this->old_values = json_decode($this->amendmentRequest->old_values);
        $this->new_values = json_decode($this->amendmentRequest->new_values);
    }
    public function render()
    {
        return view('livewire.kyc.kyc-amendment-request-show', ['new_values' => $this->new_values, 'old_values' => $this->old_values]);
    }
}
