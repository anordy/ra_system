<?php

namespace App\Http\Livewire\Approval;

use App\Enum\DisputeStatus;
use App\Enum\TaxClearanceStatus;
use App\Events\SendMail;
use App\Events\SendSms;
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
        $this->modelId = decrypt($modelId);
        $this->tax_clearence = TaxClearanceRequest::find($this->modelId);
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if ($this->checkTransition('crdm_review')) {

            try {
                $this->subject->status = TaxClearanceStatus::APPROVED;
                $this->subject->save();
    
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
    
                $emailPayload = [
                    $this->tax_clearence->businessLocation,
                    $this->subject,
                ];
                event(new SendMail('tax-clearance-approved', $emailPayload));
    
                $smsPayload = [
                    $this->tax_clearence->businessLocation->taxpayer->mobile,
                    'Your approval for tax clearance certificate of your business '.$this->tax_clearence->businessLocation->name.' has been granted, please check your email or log in to ZIDRAS to obtain your online certificate copy.'
                ];
                event(new SendSms('tax-clearance-feedback-to-taxpayer', $smsPayload));
    
                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->alert('error', 'Something went wrong, Please contact our support desk for help.');
                return;
            }

        } else {
            $this->flash('warning', 'You do not have authority to approve this request!');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required',
        ]);

        try {

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);

            $mailPayload = [
                $this->tax_clearence->businessLocation->taxpayer->email,
                'Your approval for tax clearance certificate of your business '.$this->tax_clearence->businessLocation->name.' has been rejected, please pay off all debts to be clear for approval.'
            ];

            event(new SendMail('tax-clearance-rejected', $mailPayload));
            
            $smsPayload = [
                $this->tax_clearence->businessLocation->taxpayer->mobile,
                'Your approval for tax clearance certificate of your business '.$this->tax_clearence->businessLocation->name.' has been rejected, please pay off all debts to be clear for approval.'
            ];

            event(new SendSms('tax-clearance-feedback-to-taxpayer', $smsPayload));

        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help.');
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.tax-clearence-approval-processing');
    }

}
