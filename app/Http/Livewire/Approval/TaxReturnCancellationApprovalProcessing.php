<?php

namespace App\Http\Livewire\Approval;

use App\Enum\CustomMessage;
use App\Enum\TaxVerificationStatus;
use App\Jobs\Bill\CancelBill;
use App\Models\Returns\TaxReturn;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TaxReturnCancellationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

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

        try {
            DB::beginTransaction();

            if ($this->checkTransition('commissioner_review') && $transition === 'commissioner_review') {
                $this->subject->status = TaxVerificationStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();

                // Cancel existing bill
                if ($this->subject->taxReturn->latestBill) {
                    $currentBill = $this->subject->taxReturn->latestBill;
                    CancelBill::dispatch($currentBill, 'Return Cancellation');
                }

                // Soft delete current tax return and child return
                $this->subject->return->delete();
                $this->subject->taxReturn->delete();

            }

            $this->subject->save();

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($transition === 'commissioner_review') {
                $this->customAlert('success', 'Return cancelled successfully');
                return redirect()->route('tax-return-cancellation.index');
            } else {
                $this->flash('success', 'Application approved successfully', [], redirect()->back()->getTargetUrl());
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('APPROVAL-TAX-RETURN-CANCELLATION-APPROVAL-PROCESSING-APPROVE', [$e]);
            $this->customAlert('error', CustomMessage::ERROR);
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
            if ($this->checkTransition('tax_officer_incorrect') && $transition === 'tax_officer_incorrect') {
                $this->subject->status = TaxVerificationStatus::CORRECTION;
            } else if ($this->checkTransition('commissioner_reject') && $transition === 'commissioner_reject') {
                $this->subject->status = TaxVerificationStatus::REJECTED;
            }
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Application rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('APPROVAL-TAX-RETURN-CANCELLATION-APPROVAL-PROCESSING-REJECT', [$e]);
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
        return view('livewire.approval.tax_return_cancellation');
    }

}