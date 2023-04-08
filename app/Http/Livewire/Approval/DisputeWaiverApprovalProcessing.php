<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Carbon\Carbon;
use App\Models\TaxType;
use Livewire\Component;
use App\Enum\DisputeStatus;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Dispute\GenerateAssessmentDisputeControlNo;
use App\Traits\PaymentsTrait;
use Livewire\WithFileUploads;
use App\Models\Disputes\Dispute;
use App\Services\ZanMalipo\ZmCore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use App\Traits\TaxAssessmentDisputeTrait;
use App\Models\TaxAssessments\TaxAssessment;
use App\Traits\CustomAlert;

class DisputeWaiverApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, TaxAssessmentDisputeTrait, PaymentsTrait, CustomAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $disputeReport;
    public $taxTypes;
    public $complete = '0';
    public $penaltyPercent, $penaltyAmount, $penaltyAmountDue, $interestAmountDue;
    public $interestPercent, $interestAmount, $dispute, $assesment, $total, $principal_amount_due;
    public $natureOfAttachment, $noticeReport, $settingReport;
    public $principal, $penalty, $interest, $assessment;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->dispute = Dispute::find($this->modelId);
        if(is_null($this->dispute)){
            abort(404);
        }
        $this->assessment = TaxAssessment::find($this->dispute->assesment_id);
        if(is_null($this->assessment)){
            abort(404);
        }
        $this->penalty = $this->assessment->penalty_amount;
        $this->interest = $this->assessment->interest_amount;
        $this->principal = $this->assessment->principal_amount;
        $this->taxTypes = TaxType::all();
        $this->principal_amount_due = $this->assessment->principal_amount - $this->dispute->tax_deposit;
        $this->total = $this->assessment->outstanding_amount;
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function updated($propertyName)
    {
        if ($propertyName == "penaltyPercent") {
            if ($this->penaltyPercent > 100) {
                $this->penaltyPercent = 100;
            } elseif ($this->penaltyPercent < 0 || !is_numeric($this->penaltyPercent)) {
                $this->penaltyPercent = null;
            }
            $this->penaltyAmount = ($this->assessment->penalty_amount * $this->penaltyPercent) / 100;
        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->assessment->interest_amount * $this->interestPercent) / 100;
        }

        $this->penaltyAmountDue = $this->assessment->penalty_amount - $this->penaltyAmount;
        $this->interestAmountDue = $this->assessment->interest_amount - $this->interestAmount;
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->assessment->principal_amount);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $taxType = $this->subject->taxType;

        if ($this->checkTransition('objection_manager_review')) {

            $this->validate(
                [
                    'disputeReport' => 'required|mimes:pdf',
                ]
            );

            $disputeReport = "";
            if ($this->disputeReport) {
                $disputeReport = $this->disputeReport->store('waiver_report', 'local');
            }

            $dispute = Dispute::findOrFail($this->modelId);

            DB::beginTransaction();
            try {

                $dispute->update([
                    'dispute_report' => $disputeReport ?? '',
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
            }

        }

        if ($this->checkTransition('chief_assurance_review')) {

        }

        $approveNotification = 'Approved successfully';

        if ($this->checkTransition('commisioner_review')) {
            $this->complete = "1";

            // $this->validate([
            //     'interestPercent' => ['required', 'numeric'],
            //     'penaltyPercent' => ['required', 'numeric'],
            // ]);
            DB::beginTransaction();

            try {

                $this->subject->update([
                    'penalty_rate' => $this->penaltyPercent ?? 0,
                    'interest_rate' => $this->interestPercent ?? 0,
                    'penalty_amount' => $this->penaltyAmount ?? 0,
                    'interest_amount' => $this->interestAmount ?? 0,
                ]);

                $this->assessment->update([
                    'penalty_amount' => $this->penaltyAmountDue,
                    'interest_amount' => $this->interestAmountDue,
                    'total_amount' => $this->total,
                    'outstanding_amount' => $this->total,
                    'application_status' => 'waiver',
                ]);

                $this->subject->app_status = DisputeStatus::APPROVED;
                $this->subject->save();

                // Generate control number for waived application
                if ($this->assessment->bill) {
                    CancelBill::dispatch($this->assessment->bill, 'Assessment dispute has been waived');
                    GenerateAssessmentDisputeControlNo::dispatch($this->assessment);
                } else {
                    GenerateAssessmentDisputeControlNo::dispatch($this->assessment);
                }

                DB::commit();

                $approveNotification = 'Approved and control number has been generated successful';
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }

        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', $approveNotification, [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
            return;
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {

            }

            if ($this->checkTransition('chief_assurance_reject')) {

            }

            if ($this->checkTransition('commisioner_reject')) {

                DB::beginTransaction();

                try {
                    $this->subject->app_status = DisputeStatus::REJECTED;
                    $this->subject->save();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e);
                    $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                }

            }

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
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
        return view('livewire.approval.dispute-waiver-approval-processing');
    }

}
