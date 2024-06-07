<?php

namespace App\Http\Livewire\Approval\RoadLicense;

use App\Enum\CustomMessage;
use App\Enum\PublicService\DeRegistrationStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Enum\RoadLicenseStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $inspectionDate, $passMark, $certAuthNumber, $issuedDate, $expiryDate, $capacity, $passengerNumber;

    protected $rules = [
        'inspectionDate'  => 'required|date',
        'issuedDate'  => 'required|date',
        'expiryDate'  => 'required|date',
        'passMark'  => 'required|integer',
        'certAuthNumber'  => 'required|alpha_gen',
        'capacity'  => 'nullable|alpha_gen',
        'passengerNumber'  => 'required|integer',
        'comments' => 'required|alpha_gen'
    ];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('APPROVAL-ROAD-LICENSE-APPROVAL-PROCESSING-APPROVE', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        $this->validate();

        if ($this->checkTransition('mvr_zartsa_officer_review')) {

            try {
                DB::beginTransaction();

                $this->subject->inspection_date = $this->inspectionDate;
                $this->subject->issued_date = $this->issuedDate;
                $this->subject->expire_date = $this->expiryDate;
                $this->subject->cert_auth_number = $this->certAuthNumber;
                $this->subject->passengers_no = $this->passengerNumber;
                $this->subject->pass_mark = $this->passMark;
                $this->subject->capacity = $this->capacity;

                $this->subject->status = RoadLicenseStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->urn = 'Z-' . str_pad($this->subject->id, 6, "0", STR_PAD_LEFT);
                $this->subject->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                DB::commit();
                return $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('APPROVAL-ROAD-LICENSE-APPROVAL-PROCESSING-APPROVE', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;
            }
        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('APPROVAL-ROAD-LICENSE-APPROVAL-PROCESSING-REJECT', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('APPROVAL-ROAD-LICENSE-APPROVAL-PROCESSING-REJECT', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
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
        return view('livewire.approval.road-license.approval');
    }

}
