<?php

namespace App\Http\Livewire\Installment;

use App\Enum\InstallmentRequestStatus;
use App\Models\Installment\InstallmentExtensionRequest;
use Livewire\Component;

class InstallmentExtensionRequestApprovalTable extends Component
{
    public $pending;
    public $rejected;
    public function render()
    {
        $extensions = InstallmentExtensionRequest::with('installment')->where('status', InstallmentRequestStatus::PENDING)->orderBy('created_at', 'desc')->get();

        return view('livewire.installment.installment-extension-request-approval-table', compact('extensions'));
    }
}
