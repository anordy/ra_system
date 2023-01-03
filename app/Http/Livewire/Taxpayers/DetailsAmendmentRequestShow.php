<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Taxpayer;
use App\Models\TaxpayerAmendmentRequest;
use App\Models\User;
use Livewire\Component;

class DetailsAmendmentRequestShow extends Component
{

    public $amendmentRequest;
    private $old_values;
    public $taxpayer_id;
    private $new_values;
    public $createdBy;

    public function mount($id){
        $this->amendmentRequest = TaxpayerAmendmentRequest::findOrFail(decrypt($id));
        $this->createdBy = User::findorFail($this->amendmentRequest->created_by)->fullname();
        $this->old_values = json_decode($this->amendmentRequest->old_values);
        $this->taxpayer_id = $this->amendmentRequest->taxpayer_id;
        $this->new_values = json_decode($this->amendmentRequest->new_values);

    }

    public function render()
    {
        return view('livewire.taxpayers.details-amendment-request-show', ['new_values' => $this->new_values, 'old_values' => $this->old_values]);
    }
}
