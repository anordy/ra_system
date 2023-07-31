<?php

namespace App\Http\Livewire\Approval;

use Exception;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\TaxVerificationTrait;
use App\Enum\QuantityCertificateStatus;
use App\Traits\WorkflowProcesssingTrait;
use App\Jobs\QuantityCertificate\SendQuantityCertificateSMS;
use App\Jobs\QuantityCertificate\SendQuantityCertificateMail;

class QuantityCertificateApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, TaxVerificationTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $certificate;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->certificate = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('manager_of_special_sector_review')) {
            try {
                DB::beginTransaction();

                $this->subject->status = QuantityCertificateStatus::PENDING;
                $this->subject->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                event(new SendSms(SendQuantityCertificateSMS::SERVICE, $this->certificate));
                event(new SendMail(SendQuantityCertificateMail::SERVICE, $this->certificate));

                $this->flash('success', 'Application Approved Successful', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('manager_of_special_sector_reject')) {

            DB::beginTransaction();
            try {
                $this->subject->status = QuantityCertificateStatus::CORRECTION;

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                $this->flash('success', 'Application sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
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

    public function render()
    {
        return view('livewire.approval.quantity-certificate-processing');
    }
}
