<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ExtensionRequestStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\Bill\UpdateBill;
use App\Jobs\Extension\SendExtensionApprovedMail;
use App\Jobs\Extension\SendExtensionApprovedSMS;
use App\Jobs\Extension\SendExtensionRejectedMail;
use App\Jobs\Extension\SendExtensionRejectedSMS;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ExtensionRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $extendTo;
    public $taxTypes;

    public $task;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);

        $this->registerWorkflow($modelName, $this->modelId);

        $this->task = $this->subject->pinstancesActive;
    }

    public function approve($transition)
    {
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'extendTo' => [
                    'required',
                    'date',
                    'after:'. $this->subject->extensible->curr_payment_due_date,
                    'before:'. Carbon::make($this->subject->extensible->curr_payment_due_date)->addYear()->toDateTimeString()
            ]]);
        }

        DB::beginTransaction();

        try {
            if ($this->checkTransition('debt_manager')) {
                $this->subject->extend_from = $this->subject->extensible->curr_payment_due_date;
                $this->subject->extend_to = $this->extendTo;
                $this->subject->save();
            }

            if ($this->checkTransition('accepted')) {
                $this->subject->status = ExtensionRequestStatus::APPROVED;
                $extensible = $this->subject->extensible_type::findOrFail($this->subject->extensible_id);
                $extensible->update([
                    'curr_payment_due_date' => $this->subject->extend_to
                ]);

                // If extended date is greater than current bill expiration date.
                if ($this->subject->extend_to->greaterThan($extensible->bill->expire_date)){
                    $now = Carbon::now();
                    UpdateBill::dispatch($extensible->bill, $this->subject->extend_to)->delay($now->addSeconds(2));
                }
                $this->subject->save();


                // Dispatch notification via email and mobile phone
                event(new SendSms(SendExtensionApprovedSMS::SERVICE, $this->subject));
                event(new SendMail(SendExtensionApprovedMail::SERVICE, $this->subject));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function reject($transition)
    {
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);
        DB::beginTransaction();

        try {
            if ($this->checkTransition('rejected')) {
                $this->subject->status = ExtensionRequestStatus::REJECTED;
                $this->subject->save();
            }

            // Dispatch notification via email and mobile phone
            event(new SendSms(SendExtensionRejectedSMS::SERVICE, $this->subject));
            event(new SendMail(SendExtensionRejectedMail::SERVICE, $this->subject));

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
    }

    public function render()
    {
        return view('livewire.approval.extension-request');
    }
}
