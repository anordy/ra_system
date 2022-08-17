<?php

namespace App\Http\Livewire\Approval;

use App\Enum\DisputeStatus;
use App\Enum\TaxClearanceStatus;
use App\Models\TaxClearanceRequest;
use App\Models\TaxType;
use App\Traits\PaymentsTrait;
use App\Traits\TaxAssessmentDisputeTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxClearenceApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, TaxAssessmentDisputeTrait, PaymentsTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $disputeReport;
    public $taxTypes;
    public $penaltyPercent, $penaltyAmount, $penaltyAmountDue, $interestAmountDue;
    public $interestPercent, $interestAmount, $tax_clearence, $assesment, $total;
    public $natureOfAttachment, $noticeReport, $settingReport;

    public function mount($modelName, $modelId)
    {

        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->tax_clearence = TaxClearanceRequest::find($this->modelId);
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $modelId);
    }

    public function approve($transtion)
    {
        if ($this->checkTransition('crdm_review')) {
        }

        try {
            $this->subject->status = TaxClearanceStatus::APPROVED;
            $this->subject->save();

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong.');
            return;
        }
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required',
        ]);

        try {
            $this->doTransition($transtion, ['status' => 'reject', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.tax-clearence-approval-processing');
    }

}
