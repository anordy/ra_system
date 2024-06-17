<?php

namespace App\Http\Livewire\Installment;

use App\Enum\InstallmentRequestStatus;
use App\Models\Installment\InstallmentExtensionRequest;
use App\Traits\CustomAlert;
use Livewire\Component;

class InstallmentExtensionRequestTable extends Component
{
    use CustomAlert;

    public $pending;
    public $rejected;

    public function render()
    {
        $extensions = InstallmentExtensionRequest::with('installment')->orderBy('created_at', 'desc')->get();

        if ($this->rejected){
            $extensions = InstallmentExtensionRequest::with('installment')->where('status',InstallmentRequestStatus::REJECTED)->orderBy('created_at', 'desc')->get();
        }
        return view('livewire.installment.installment-extension-request-table',compact('extensions'));
    }
}
