<?php

namespace App\Http\Livewire\Kyc;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\KYC;
use App\Models\KycAmendmentRequest;
use App\Traits\VerificationTrait;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class KycAmendmentRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, VerificationTrait;
    public $modelId;
    public $modelName;
    public $comments;
    public $amendmentRequest;
    public $kyc_id;
    public $business;
    public $consultant;

    public function mount($modelName, $modelId, $amendmentRequest)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->amendmentRequest = $amendmentRequest;
        $this->kyc_id = $amendmentRequest->kyc_id;
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        try {
            if ($this->checkTransition('registration_manager_review')) {

                $new_values = json_decode($this->amendmentRequest->new_values, true);

                $kyc_details = $new_values;

                /** Update kyc information */
                $kyc = KYC::findOrFail($this->kyc_id);
                $kyc->update($kyc_details);
                $this->subject->status = KycAmendmentRequest::APPROVED;
                $message = 'We are writing to inform you that some of your ZIDRAS Know your Customer (kyc) information has been changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                $this->sendEmailToUser($kyc, $message);
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required']);
        $kyc = KYC::findOrFail($this->kyc_id);

        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = KycAmendmentRequest::REJECTED;

                $message = 'We are writing to inform you that some of your Request for ZIDRAS kyc personal information has been rejected.';
                $this->sendEmailToUser($kyc, $message);
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
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

    public function sendEmailToUser($data, $message)
    {
        $smsPayload = [
            'phone' => $data->phone,
            'message' => 'Hello, {$data->first_name}. {$message}',
        ];

        $emailPayload = [
            'email' => $data->email,
            'userName' => $data->first_name,
            'message' => $message,
        ];

        event(new SendMail('taxpayer-amendment-notification', $emailPayload));
        event(new SendSms('taxpayer-amendment-notification', $smsPayload));
    }

    public function render()
    {
        return view('livewire.kyc.kyc-amendment-request-approval-processing');
    }
}
