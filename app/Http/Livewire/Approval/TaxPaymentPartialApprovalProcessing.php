<?php

namespace App\Http\Livewire\Approval;

use App\Enum\CustomMessage;
use App\Enum\ReturnStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TaxPaymentPartialApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        DB::beginTransaction();
        try {
            if ($this->checkTransition('department_commissioner_review')) {
                $this->subject->status = ReturnStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->staff_id = Auth::id();
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status === ReturnStatus::APPROVED && $transition === 'department_commissioner_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "Hello {$this->subject->taxpayer->fullname}, your application request for partial payment has been approved, you will receive your payment control number shortly."]));
            }

            $this->generateLedgerControlNumber($this->subject);

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('department_commissioner_reject')) {
            try {
                DB::beginTransaction();
                $this->subject->status = ReturnStatus::REJECTED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->staff_id = Auth::id();
                $this->subject->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                DB::commit();

                if ($this->subject->status === ReturnStatus::REJECTED && $transition === 'department_commissioner_reject') {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "Hello {$this->subject->taxpayer->fullname}, your application request for partial payment has been rejected because of the following reason: {$this->comments}."]));
                }

                $this->flash('success', 'Application Rejected', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->customAlert('error', CustomMessage::error());
            }
        }
    }


    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', CustomMessage::ARE_YOU_SURE, [
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
        return view('livewire.approval.tax-payment-partial-approval');
    }
}
