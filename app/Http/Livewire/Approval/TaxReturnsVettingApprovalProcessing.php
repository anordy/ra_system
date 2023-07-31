<?php

namespace App\Http\Livewire\Approval;

use Exception;
use App\Models\Role;
use App\Models\User;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Taxpayer;
use App\Enum\VettingStatus;
use App\Traits\CustomAlert;
use App\Enum\TaxClaimStatus;
use App\Traits\PaymentsTrait;
use App\Traits\PenaltyForDebt;
use App\Traits\TaxClaimsTrait;
use App\Traits\VatReturnTrait;
use App\Traits\TaxReturnHistory;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use App\Traits\TaxVerificationTrait;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\WorkflowProcesssingTrait;
use App\Jobs\Vetting\SendVettedReturnSMS;
use App\Jobs\Vetting\SendVettedReturnMail;
use App\Notifications\DatabaseNotification;
use App\Jobs\Vetting\SendToCorrectionReturnSMS;
use App\Jobs\Vetting\SendToCorrectionReturnMail;

class TaxReturnsVettingApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait, TaxVerificationTrait, TaxReturnHistory, TaxClaimsTrait, VatReturnTrait;

    public $modelId;
    public $modelName;
    public $comments;

    public $return;
    public $claim_data;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->return = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function previewPenalties() {
        $tax_return = TaxReturn::selectRaw('
                tax_returns.*, 
                (MONTHS_BETWEEN(CURRENT_DATE, CAST(filing_due_date as date))) as periods, 
                (MONTHS_BETWEEN(CAST(curr_payment_due_date as date), CURRENT_DATE)) as penatableMonths
            ')
            ->whereRaw("CURRENT_DATE - CAST(filing_due_date as date) > 30") // Since filing due date is of last month
            ->where('vetting_status', VettingStatus::SUBMITTED)
            ->where('id', $this->return->id)
            ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
            ->get()
            ->firstOrFail();

        // Before vetting only child return may have penalties
        $penalties = $tax_return->return->penalties;

        $preVettingPenaltyIterations = $penalties->count();
        $postVettingPenaltyIterations = round($tax_return->periods);

        $penaltyIterationsToBeAdded = $postVettingPenaltyIterations - $preVettingPenaltyIterations;

        try {
            return PenaltyForDebt::getPostVettingPenalties($tax_return, $penaltyIterationsToBeAdded);
        } catch (Exception $e) {
            Log::error($e);
            throw new Exception('Failed to preview penalties');
        }

    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if ($this->checkTransition('return_vetting_officer_review')) {
            $this->validate(
                [
                    'comments' => ['nullable'],
                ],
            );

            DB::beginTransaction();
            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                
                // Generate Penalties Additional Penalties
                $tax_return = $this->previewPenalties();

                $this->return->vetting_status = VettingStatus::VETTED;
                $this->return->return->vetting_status = VettingStatus::VETTED;
                $this->return->save();
                $this->return->return->save();

                DB::commit();

                // Trigger verification
                $this->triggerTaxVerifications($this->return->return, auth()->user());

                // Generate control number
                $this->generateReturnControlNumber($tax_return);

                //triggering claim
                if ($this->return->return_type == VatReturn::class) {
                    if ($this->return->return->claim_status == 'claim') {

                        $claim = $this->triggerClaim(abs($this->return->return->total_amount_due), $this->return->return->currency, $this->return->return);

                        $taxpayer = Taxpayer::query()->where('id', $this->return->return->filed_by_id)->first();
                        $taxpayer = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
                        $role = Role::query()->where('name', 'Administrator')->first();
                        $admins = User::query()->where('role_id', $role->id)->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new DatabaseNotification(
                                $subject = 'TAX CLAIMING',
                                $message = 'You have a new request for tax claim from ' . $taxpayer . '',
                                $href = 'claims.show',
                                $hrefText = 'View',
                                $hrefParameters = $claim->id,
                            ));
                        }
                    }
                }
                //saving credit brought forward(claim)

                if ($this->return->return->credit_brought_forward > 0) {
                    $this->claim_data = VatReturn::query()->selectRaw('payment_status, tax_credits.amount, payment_method, installments_count,
        tax_credits.id as credit_id, tax_claims.old_return_id, tax_claims.old_return_type, tax_claims.currency')
                        ->leftJoin('tax_claims', 'tax_claims.old_return_id', '=', 'vat_returns.id')
                        ->leftJoin('tax_credits', 'tax_credits.claim_id', '=', 'tax_claims.id')
                        ->where('vat_returns.claim_status', '=', TaxClaimStatus::CLAIM)
                        ->where('vat_returns.business_location_id', $this->return->return->business_location_id)
                        ->where('tax_claims.status', 'approved')
                        ->where('tax_credits.payment_status', '!=', 'paid')
                        ->orderBy('tax_credits.id')->limit(1)
                        ->first();
                    $this->savingClaimPayment($this->return->return->credit_brought_forward);
                }

                event(new SendSms(SendVettedReturnSMS::SERVICE, $this->return));
                event(new SendMail(SendVettedReturnMail::SERVICE, $this->return));

                $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
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

        if ($this->checkTransition('application_filled_incorrect')) {

            DB::beginTransaction();
            try {
                $this->subject->vetting_status = VettingStatus::CORRECTION;
                $this->subject->return->vetting_status = VettingStatus::CORRECTION;
                $this->subject->return->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                $this->saveHistory($this->subject);

                event(new SendSms(SendToCorrectionReturnSMS::SERVICE, $this->return));
                event(new SendMail(SendToCorrectionReturnMail::SERVICE, $this->return));

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
        return view('livewire.approval.tax_returns_vetting');
    }
}
