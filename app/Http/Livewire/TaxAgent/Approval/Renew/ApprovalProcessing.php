<?php

namespace App\Http\Livewire\TaxAgent\Approval\Renew;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\RenewTaxAgentRequest;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Notifications\DatabaseNotification;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $taxTypes;
    public $shares;
    public $renew;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->renew = RenewTaxAgentRequest::findOrFail($this->subject->id);
    }


    public function approve($transition)
    {
        if ($this->renew == null) {
            $this->alert('error', 'Tax Consultant does not exist');
            return;
        }
        $feeType = 'Renewal Fee';
        // TODO: check if queried objects exist
        $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'duration', 'is_citizen', 'currency')
            ->where('category', $feeType)->where('is_citizen', $this->renew->tax_agent->taxpayer->is_citizen)->first();
        if ($fee == null) {
            $this->alert('error', 'The fee does not exist');
            return;
        }

        $transition = $transition['data']['transition'];
        if ($this->checkTransition('registration_officer_review')) {
            // registration officer verifying request
            $amount = $fee->amount;
            $used_currency = $fee->currency;
            $tax_type = TaxType::query()->where('code', TaxType::TAX_CONSULTANT)->first();
            if ($tax_type == null) {
                $this->alert('error', 'The tax type does not exist');
                return;
            }
            $billitems = [
                [
                    'billable_id' => $this->renew->id,
                    'billable_type' => get_class($this->renew),
                    'fee_id' => $fee->id,
                    'fee_type' => get_class($fee),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $amount,
                    'currency' => $used_currency,
                    'gfs_code' => $tax_type->gfs_code,
                    'tax_type_id' => $tax_type->id
                ]
            ];

            if ($amount > 0) {
                $this->generateTaxAgentRenewControlNo($this->renew, $billitems, $used_currency);
            } else {
                $this->alert('error', 'Bill amount can not be zero');
            }

            $this->renew->tax_agent->taxpayer->notify(new DatabaseNotification(
                $subject = 'RENEW TAX CONSULTANT VERIFICATION',
                $message = 'Your application has been verified. Please check control number to pay',
                $href = 'taxagent.apply',
                $hrefText = 'view'
            ));
        }

        if ($this->checkTransition('registration_manager_review')) {
            //registration manager approving request
            $this->renew->status = TaxAgentStatus::APPROVED;
            $this->renew->renew_first_date = Carbon::now();
            $this->renew->renew_expire_date = Carbon::now()->addYear($fee->duration)->toDateTimeString();
            $this->renew->save();

            $this->renew->tax_agent->taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX-CONSULTANT APPROVAL',
                $message = 'Your application has been approved',
                $href = 'taxagent.apply',
                $hrefText = 'view'
            ));

            if (config('app.env') == 'production') {
                event(new SendMail('tax-agent-registration-approval', $this->agent->taxpayer_id));
                event(new SendSms('tax-agent-registration-approval', $this->agent->taxpayer_id));
            }

            $this->subject->approved_at = Carbon::now()->toDateTimeString();
            $this->subject->status = TaxAgentStatus::APPROVED;
        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
            return;
        }

        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = TaxAgentStatus::CORRECTION;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
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
        return view('livewire.tax-agent.approval.renew.approval-processing');
    }
}
