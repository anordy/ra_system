<?php

namespace App\Http\Livewire\Approval;

use App\Enum\BillStatus;
use App\Enum\CondominiumStatus;
use App\Enum\withholding_agentPaymentCategoryStatus;
use App\Enum\withholding_agentStatus;
use App\Enum\withholding_agentTypeStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Jobs\withholding_agentTax\Sendwithholding_agentTaxApprovalMail;
use App\Jobs\withholding_agentTax\Sendwithholding_agentTaxApprovalSMS;
use App\Jobs\withholding_agentTax\Sendwithholding_agentTaxCorrectionMail;
use App\Jobs\withholding_agentTax\Sendwithholding_agentTaxCorrectionSMS;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\withholding_agentTax\withholding_agentPayment;
use App\Models\WithholdingAgentStatus;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\withholding_agentTaxTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class WithholdingAgentApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public $withholding_agent;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->withholding_agent = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('registration_officer_review')) {
            $this->validate(
                [
                    'comments' => ['nullable'],
                ],
            );
        }

        if ($this->checkTransition('registration_manager_review')) {
            $this->validate(
                [
                    'comments' => ['nullable'],
                ],
            );

            $this->withholding_agent->app_status = WithholdingAgentStatus::APPROVED;
            $this->withholding_agent->status = 'active';
            $this->withholding_agent->approved_on = Carbon::now();
            $this->withholding_agent->save();
        }

        DB::beginTransaction();
        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();


            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
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

                $this->withholding_agent->app_status = WithholdingAgentStatus::CORRECTION;


                $this->withholding_agent->save();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                DB::commit();

                $this->flash('success', 'Registration sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
        if ($this->checkTransition('registration_manager_reject')) {
            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                $this->flash('success', 'Application Rejected', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                Log::error('Error: ' . $e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ]);
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
        return view('livewire.approval.withholding-agent_approval');
    }
}
