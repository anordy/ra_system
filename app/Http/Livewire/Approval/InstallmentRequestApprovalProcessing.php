<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Carbon\Carbon;
use App\Models\TaxType;
use Livewire\Component;
use App\Enum\PaymentMethod;
use App\Jobs\Bill\CancelBill;
use App\Traits\PaymentsTrait;
use Livewire\WithFileUploads;
use App\Enum\ApplicationStatus;
use App\Enum\InstallmentStatus;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enum\ExtensionRequestStatus;
use App\Models\Returns\ReturnStatus;
use App\Enum\InstallmentRequestStatus;
use App\Models\Installment\Installment;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InstallmentRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $installmentPhases;

    public $task;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->taxTypes = TaxType::all();

        $this->registerWorkflow($modelName, $this->modelId);

        $this->task = $this->subject->pinstancesActive;

        if ($this->task != null) {
            $operators = json_decode($this->task->operators);
            if (gettype($operators) != "array") {
                $operators = [];
            }
        }
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'installmentPhases' => ['required', 'numeric', 'min:1', 'max:12'],
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->checkTransition('debt_manager')) {
                $this->subject->installment_from = $this->subject->installable->curr_payment_due_date;
                $this->subject->installment_to = Carbon::make($this->subject->installable->curr_payment_due_date)->addMonths($this->installmentPhases);
                $this->subject->installment_count = $this->installmentPhases;
                $this->subject->save();
            }

            if ($this->checkTransition('accepted')) {
                $this->subject->status = InstallmentRequestStatus::APPROVED;
                $installable = $this->subject->installable_type::findOrFail($this->subject->installable_id);

                // Update tax return details
                $installable->update([
                    'curr_payment_due_date' => $this->subject->installment_to,
                    'payment_method' => PaymentMethod::INSTALLMENT,
                    'application_status' => ApplicationStatus::INSTALLMENT
                ]);

                // Cancel Control No.
                if ($installable->bill){
                    $now = Carbon::now();
                    CancelBill::dispatch($installable->bill, 'Debt shifted to installments')->delay($now->addSeconds(10));
                }

                // Create installment record
                Installment::create([
                    'installable_type' => $this->subject->installable_type,
                    'installable_id' => $this->subject->installable_id,
                    'location_id' => $this->subject->location_id,
                    'business_id' => $this->subject->business_id,
                    'tax_type_id' => $this->subject->tax_type_id,
                    'installment_request_id' => $this->subject->id,
                    'installment_from' => $this->subject->installment_from,
                    'installment_to' => $this->subject->installment_to,
                    'installment_count' => $this->subject->installment_count,
                    'amount' => $installable->outstanding_amount,
                    'currency' => $installable->currency,
                    'status' => InstallmentStatus::ACTIVE
                ]);

                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', $e->getMessage());
            return;
        }

    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('rejected')) {
                $this->subject->status = ExtensionRequestStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, please try again later.');
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
        return view('livewire.approval.installment-request');
    }
}
