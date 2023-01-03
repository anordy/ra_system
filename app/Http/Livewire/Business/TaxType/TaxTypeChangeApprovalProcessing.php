<?php

namespace App\Http\Livewire\Business\TaxType;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use App\Models\TaxType;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessTaxTypeChange;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class TaxTypeChangeApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $taxchange;
    public $selectedTaxTypes = [];
    public $oldTaxTypes = [];
    public $taxTypes;
    public $from_tax_type_id;
    public $to_tax_type_id;
    public $to_tax_type_currency;
    public $effective_date;
    public $today;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->taxchange = BusinessTaxTypeChange::findOrFail($this->modelId);
        $this->to_tax_type_id = $this->taxchange->to_tax_type_id;
        $this->from_tax_type_id = $this->taxchange->from_tax_type_id;
        $this->to_tax_type_currency = $this->taxchange->to_tax_type_currency;
        $this->taxTypes   = TaxType::select('id', 'name')->where('category', 'main')->get();
        $this->today = Carbon::today()->addDay()->format('Y-m-d');
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'effective_date' => 'required', 
            'to_tax_type_currency' => 'required', 
            'to_tax_type_id' => 'required'
        ]);

        if ($this->to_tax_type_id == $this->from_tax_type_id) {
            $this->alert('warning', 'You cannot change to an existing tax type');
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->checkTransition('registration_manager_review')) {

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->effective_date = $this->effective_date;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

                $notification_payload = [
                    'tax_change' => $this->taxchange,
                ];

                DB::commit();
                
                event(new SendMail('change-tax-type-approval', $notification_payload));
                event(new SendSms('change-tax-type-approval', $notification_payload));

            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required']);
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
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
        return view('livewire.approval.taxtype-change');
    }
}
