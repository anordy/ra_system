<?php

namespace App\Http\Livewire\Approval;

use App\Enum\DebtPaymentMethod;
use App\Enum\ExtensionRequestStatus;
use App\Enum\InstallmentRequestStatus;
use App\Enum\InstallmentStatus;
use App\Models\Debts\Debt;
use App\Models\Installment\Installment;
use App\Models\Returns\TaxReturn;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Exception;
use App\Models\TaxType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
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
        $this->modelId   = $modelId;
        $this->taxTypes = TaxType::all();

        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;

        if ($this->task != null) {
            $operators = json_decode($this->task->operators);
            if (gettype($operators) != "array") {
                $operators = [];
            }
        }
    }

    public function approve($transtion)
    {

        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'installmentPhases' => ['required', 'numeric', 'min:1', 'max:12'],
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->checkTransition('debt_manager')) {
                $this->subject->installment_from = $this->subject->taxReturn->curr_payment_due_date;
                $this->subject->installment_to = Carbon::make($this->subject->taxReturn->curr_payment_due_date)->addMonths($this->installmentPhases);
                $this->subject->installment_count = $this->installmentPhases;
                $this->subject->save();
            }

            if ($this->checkTransition('accepted')) {
                $this->subject->status = InstallmentRequestStatus::APPROVED;
                $taxReturn = TaxReturn::findOrFail($this->subject->tax_return_id);

                // Update debt details
                $taxReturn->update([
                    'curr_payment_due_date' => $this->subject->installment_to,
                    'payment_method' => DebtPaymentMethod::INSTALLMENT
                ]);

                // Cancel Control No.
                if ($taxReturn->bill){
                    $this->cancelBill($taxReturn->bill, 'Debt shifted to installments');
                }

                // Create installment record
                Installment::create([
                    'tax_return_id' => $this->subject->tax_return_id,
                    'location_id' => $this->subject->location_id,
                    'business_id' => $this->subject->business_id,
                    'tax_type_id' => $this->subject->tax_type_id,
                    'installment_request_id' => $this->subject->id,
                    'installment_from' => $this->subject->installment_from,
                    'installment_to' => $this->subject->installment_to,
                    'installment_count' => $this->subject->installment_count,
                    'amount' => $taxReturn->outstanding_amount,
                    'currency' => $taxReturn->currency,
                    'status' => InstallmentStatus::ACTIVE
                ]);

                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', $e->getMessage());
            return;
        }

    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('rejected')) {
                $this->subject->status = ExtensionRequestStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $this->alert('error', 'Something went wrong, please try again later.');
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.installment-request');
    }
}
