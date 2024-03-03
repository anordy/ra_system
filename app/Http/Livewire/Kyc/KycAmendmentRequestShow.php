<?php

namespace App\Http\Livewire\Kyc;

use App\Models\KycAmendmentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class KycAmendmentRequestShow extends Component
{
    public $amendmentRequest;
    private $old_values;
    public $taxpayer_id;
    private $new_values;
    public $createdBy;

    public function mount($id)
    {
        try {
            $this->amendmentRequest = KycAmendmentRequest::findOrFail(decrypt($id));
            $this->createdBy = User::findorFail($this->amendmentRequest->created_by)->fullname();
            $this->taxpayer_id = $this->amendmentRequest->taxpayer_id;
            $this->old_values = json_decode($this->amendmentRequest->old_values);
            $this->new_values = json_decode($this->amendmentRequest->new_values);
        } catch (\Exception $exception) {
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator for support.');
        }
    }

    public function render()
    {
        return view('livewire.kyc.kyc-amendment-request-show', ['new_values' => $this->new_values, 'old_values' => $this->old_values]);
    }
}
