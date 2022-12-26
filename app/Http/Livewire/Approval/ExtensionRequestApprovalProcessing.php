<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Debts\Debt;
use App\Jobs\Bill\UpdateBill;
use App\Traits\PaymentsTrait;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enum\ExtensionRequestStatus;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ExtensionRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, PaymentsTrait;

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
        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'extendTo' => ['required'],
            ]);
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
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
            return;
        }
    }

    public function reject($transition)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);
        DB::beginTransaction();

        try {
            if ($this->checkTransition('rejected')) {
                $this->subject->status = ExtensionRequestStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
            return;
        }
    }

    public function render()
    {
        return view('livewire.approval.extension-request');
    }
}
