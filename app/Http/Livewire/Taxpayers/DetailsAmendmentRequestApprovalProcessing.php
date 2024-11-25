<?php

namespace App\Http\Livewire\Taxpayers;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use App\Models\TaxpayerAmendmentRequest;
use App\Traits\VerificationTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class DetailsAmendmentRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, VerificationTrait;
    public $modelId;
    public $modelName;
    public $comments;
    public $amendmentRequest;
    public $taxpayer_id;
    public $business;
    public $consultant;

    public function mount($modelName, $modelId, $amendmentRequest)
    {
        try {
            $this->modelName = $modelName;
            $this->modelId = decrypt($modelId);
            $this->amendmentRequest = $amendmentRequest;
            $this->taxpayer_id = $amendmentRequest->taxpayer_id;
            $this->registerWorkflow($modelName, $this->modelId);
        } catch (\Exception $exception){
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator.');
        }
    }

    public function approve($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('Transition data not found');
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
        $transition = $transition['data']['transition'];
        try {
            DB::beginTransaction();
            $taxpayer = Taxpayer::findOrFail($this->taxpayer_id);

            if ($this->checkTransition('registration_manager_review')) {

                    $new_values = json_decode($this->amendmentRequest->new_values, true);

                    $taxpayer_details = $new_values;

                    /** Update taxpayer information */
                    $taxpayer->update($taxpayer_details);
                    $this->sign($taxpayer);
                    $this->subject->status = TaxpayerAmendmentRequest::APPROVED;
                    
                    $this->subject->save();
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            if ($this->subject->status = TaxpayerAmendmentRequest::APPROVED) {
                $message = 'We are writing to inform you that some of your ZIDRAS taxpayer personal information has been changed in our records. If you did not request these changes or if you have any concerns, please contact us immediately.';
                $this->sendEmailToUser($taxpayer, $message);
            }
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('Transition data not found');
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);
        $taxpayer = Taxpayer::findOrFail($this->taxpayer_id);

        try {
            DB::beginTransaction();
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = TaxpayerAmendmentRequest::REJECTED;
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $message = 'We are writing to inform you that some of your Request for ZIDRAS taxpayer personal information has been rejected.';
            $this->sendEmailToUser($taxpayer, $message);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::commit();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
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

    public function sendEmailToUser($data, $message)
    {
        if ($data && $message){
            $smsPayload = [
                'phone' => $data->phone,
                'message' => "Hello, {$data->first_name}. {$message}",
            ];

            $emailPayload = [
                'email' => $data->email,
                'userName' => $data->first_name,
                'message' => $message,
            ];

            event(new SendMail('taxpayer-amendment-notification', $emailPayload));
            event(new SendSms('taxpayer-amendment-notification', $smsPayload));
        }
    }

    public function render()
    {
        return view('livewire.taxpayers.details-amendment-request-approval-processing');
    }
}
