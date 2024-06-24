<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ApplicationStatus;
use App\Enum\InstallmentRequestStatus;
use App\Enum\InstallmentStatus;
use App\Enum\PaymentMethod;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Installment\SendInstallmentApprovedMail;
use App\Jobs\Installment\SendInstallmentApprovedSMS;
use App\Models\Installment\Installment;
use App\Models\Installment\InstallmentExtensionRequest;
use App\Models\InterestRate;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ApprovalInstallmentExtension extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;
    public $installmentPhases;
    public $taxTypes;

    public $task;


    protected $listeners = [
        'approve', 'reject','rejected'
    ];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->taxTypes = TaxType::all();

        $this->registerWorkflow($modelName, $this->modelId);
        $this->task = $this->subject;
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);


        try {
            DB::beginTransaction();

            if ($this->checkTransition('accepted')) {

                $newDate = $this->subject->extension_date;
                $listId = $this->subject->list_id;

                $this->subject->status = InstallmentRequestStatus::APPROVED;

                $installable = InstallmentExtensionRequest::findOrFail($this->subject->id);

                // Update tax return details
                $installable->update([
                    'status' => InstallmentRequestStatus::APPROVED
                ]);

                $list = DB::table('installment_lists')->where('id',$listId)->update([
                    'due_date'=>$newDate
                ]);

                if($list >= 1){
                    $this->subject->save();
                }




                // Dispatch notification via email and mobile phone
//                event(new SendSms(SendInstallmentApprovedSMS::SERVICE, $installment));
//                event(new SendMail(SendInstallmentApprovedMail::SERVICE, $installment));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
            return;
        }

    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);


        try {
            DB::beginTransaction();

            if ($this->checkTransition('rejected')) {

                $this->subject->status = InstallmentRequestStatus::REJECTED;

                $installable = InstallmentExtensionRequest::findOrFail($this->subject->id);

                // Update tax return details
                $installable->update([
                    'status' => InstallmentRequestStatus::REJECTED
                ]);

                $this->subject->save();

                // Dispatch notification via email and mobile phone
//                event(new SendSms(SendInstallmentApprovedSMS::SERVICE, $installment));
//                event(new SendMail(SendInstallmentApprovedMail::SERVICE, $installment));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact support for assistance.');
            return;
        }

    }

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
        return view('livewire.approval.approval-installment-extension');
    }
}
