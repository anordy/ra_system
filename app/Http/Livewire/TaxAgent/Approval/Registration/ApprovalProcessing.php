<?php

namespace App\Http\Livewire\TaxAgent\Approval\Registration;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\BusinessLocation;
use App\Models\LumpSumPayment;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentApproval;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxRegion;
use App\Models\TaxType;
use App\Notifications\DatabaseNotification;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;
    public $taxTypes;
    public $shares;
    public $agent;

    public function mount($modelName, $modelId)
    {
//        todo: encrypt modelID
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->agent = TaxAgent::findOrFail($this->subject->id);

    }


    public function approve($transition)
    {
        if ($this->agent == null) {
            $this->customAlert('error', 'Tax Consultant does not exist');
            return;
        }

        $type = 'Registration';
//        // todo: check if queried objects exist
        $duration = TaPaymentConfiguration::select('id', 'category', 'duration', 'is_citizen')
            ->where('category', $type)->where('is_citizen', $this->agent->taxpayer->is_citizen)->first();
        if ($duration == null) {
            $this->customAlert('error', 'The duration for consultant registration does not exist');
            return;
        }

        $transition = $transition['data']['transition'];
        DB::beginTransaction();
        if ($this->checkTransition('registration_officer_review')) {
            $this->agent->status = TaxAgentStatus::APPROVED;
            $this->agent->app_first_date = Carbon::now();
            $this->agent->app_expire_date = Carbon::now()->addYear($duration->duration)->toDateTimeString();
            $this->agent->save();

            $this->agent->generateReferenceNo();
            $taxpayer = Taxpayer::find($this->agent->taxpayer_id);// todo: check if object exists
            if (empty($taxpayer)) {
                $this->customAlert('error', 'This taxpayer does not exist');
                return;
            }

            $taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX-CONSULTANT APPROVAL',
                $message = 'Your application has been approved',
                $href = 'taxagent.apply',
                $hrefText = 'view'
            ));

            if (config('app.env') == 'production') {
                event(new SendMail('tax-agent-registration-approval', $taxpayer->id));
                event(new SendSms('tax-agent-registration-approval', $taxpayer->id));
            }

            $this->subject->approved_at = Carbon::now()->toDateTimeString();
            $this->subject->status = TaxAgentStatus::APPROVED;

        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]) ;
            DB::commit();
            $this->customAlert('success', 'Approved successfully');
            return redirect()->route('taxagents.active-show', encrypt($this->subject->id));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong');
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
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = TaxAgentStatus::CORRECTION;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong');
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
        return view('livewire.tax-agent.approval.registration.approval-processing');
    }
}
