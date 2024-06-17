<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ApplicationStatus;
use App\Enum\BillStatus;
use App\Enum\InstallmentRequestStatus;
use App\Enum\InstallmentStatus;
use App\Enum\PaymentMethod;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Installment\SendInstallmentApprovedMail;
use App\Jobs\Installment\SendInstallmentApprovedSMS;
use App\Jobs\Installment\SendInstallmentRejectedMail;
use App\Jobs\Installment\SendInstallmentRejectedSMS;
use App\Models\Installment\Installment;
use App\Models\InterestRate;
use App\Models\TaxType;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class InstallmentRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads, PaymentsTrait, PenaltyTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $installmentPhases;
    public $taxTypes;

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
        
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'installmentPhases' => ['required', 'numeric', 'min:1', 'max:12'],
            ]);
        }

        try {
            DB::beginTransaction();

            if ($this->checkTransition('debt_manager')) {
                $this->subject->installment_from = $this->subject->installable->curr_payment_due_date;
                $this->subject->installment_to = Carbon::make($this->subject->installable->curr_payment_due_date)->addDays(30 * $this->installmentPhases);
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

                //                calculate interest total then divide the money for new installment

                $interestRate = InterestRate::where('year', Carbon::now()->year)->firstOrFail()->rate;
                $newInterest = $this->calculateInterest($installable->outstanding_amount, $interestRate, $this->subject->installment_count);

                $newFigure = $newInterest + $installable->outstanding_amount;

                // Create installment record
                $installment = Installment::create([
                    'installable_type' => $this->subject->installable_type,
                    'installable_id' => $this->subject->installable_id,
                    'location_id' => $this->subject->location_id,
                    'business_id' => $this->subject->business_id,
                    'tax_type_id' => $this->subject->tax_type_id,
                    'installment_request_id' => $this->subject->id,
                    'installment_from' => $this->subject->installment_from,
                    'installment_to' => $this->subject->installment_to,
                    'installment_count' => $this->subject->installment_count,
                    'amount' => $newFigure,
                    'currency' => $installable->currency,
                    'status' => InstallmentStatus::ACTIVE
                ]);

                $this->subject->save();


                // Create the installment list based on installment count
                $installmentListCreated = $this->createInstallmentList($installment, $this->subject->installment_count,$newFigure);

                if (!$installmentListCreated) {
                    throw new Exception('Failed to create installment list.');
                }

                // Dispatch notification via email and mobile phone
                event(new SendSms(SendInstallmentApprovedSMS::SERVICE, $installment));
                event(new SendMail(SendInstallmentApprovedMail::SERVICE, $installment));
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

    // Function to create installment list based on installment count
    private function createInstallmentList($installment, $installmentCount, $newFigure)
    {
        $installmentAmount = $newFigure / $installmentCount;
        $installmentStartDate = Carbon::make($installment->installment_from);

        try {
            for ($i = 0; $i < $installmentCount; $i++) {
                $dueDate = $installmentStartDate->copy()->addDays(30 * $i);

                DB::table('installment_lists')->insert([
                    'installment_id' => $installment->id,
                    'amount' => $installmentAmount,
                    'currency' => $installment->currency,
                    'due_date' => $dueDate,
                    'status' => BillStatus::SUBMITTED,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            return true;
        } catch (Exception $e) {
            Log::error('INSTALLMENT-LIST',['MESSAGE'=>$e->getMessage(),'TRACE'=>$e->getTrace()]  );
            return false;
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
                $this->subject->status = InstallmentStatus::REJECTED;
                $this->subject->save();
            }

            // Dispatch notification via email and mobile phone
            event(new SendSms(SendInstallmentRejectedSMS::SERVICE, $this->subject));
            event(new SendMail(SendInstallmentRejectedMail::SERVICE, $this->subject));

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
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
        return view('livewire.approval.installment-request');
    }
}
