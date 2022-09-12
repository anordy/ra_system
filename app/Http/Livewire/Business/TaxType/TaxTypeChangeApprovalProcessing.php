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

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->taxchange = BusinessTaxTypeChange::findOrFail($this->modelId);
        $this->to_tax_type_id = $this->taxchange->to_tax_type_id;
        $this->from_tax_type_id = $this->taxchange->from_tax_type_id;
        $this->to_tax_type_currency = $this->taxchange->to_tax_type_currency;
        $this->taxTypes   = TaxType::select('id', 'name')->where('category', 'main')->get();
    }


    public function approve($transtion)
    {
        if ($this->to_tax_type_id == $this->from_tax_type_id) {
            $this->alert('warning', 'You cannot change to existing tax type');
            return;
        }

        DB::beginTransaction();
        try {
            if ($this->checkTransition('registration_manager_review')) {

                $current_tax_type = BusinessTaxType::where('business_id', $this->taxchange->business_id)
                    ->where('tax_type_id', $this->taxchange->from_tax_type_id)
                    ->firstOrFail();

                $current_tax_type->update([
                    'tax_type_id' => $this->to_tax_type_id,
                    'currency' => $this->to_tax_type_currency,
                ]);

                $this->subject->status = BusinessStatus::APPROVED;

                $notification_payload = [
                    'tax_type' => $current_tax_type,
                    'tax_change' => $this->taxchange,
                    'time' => Carbon::now()->format('d-m-Y')
                ];

                DB::commit();
                
                event(new SendMail('change-tax-type-approval', $notification_payload));
                event(new SendSms('change-tax-type-approval', $notification_payload));

            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function reject($transtion)
    {
        $this->validate(['comments' => 'required']);
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }


    public function render()
    {
        return view('livewire.approval.taxtype-change');
    }
}
