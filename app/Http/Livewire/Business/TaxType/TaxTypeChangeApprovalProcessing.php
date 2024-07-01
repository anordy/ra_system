<?php

namespace App\Http\Livewire\Business\TaxType;

use App\Enum\CustomMessage;
use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use App\Models\TaxType;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessTaxTypeChange;
use App\Models\Returns\Vat\SubVat;
use App\Traits\WorkflowProcesssingTrait;
use App\Traits\CustomAlert;


class TaxTypeChangeApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;
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
    public $subVatOptions = [];
    public $showSubVatOptions = false;
    public $sub_vat_id;

    public function mount($modelName, $modelId)
    {
        try {
            $this->modelName = $modelName;
            $this->modelId = decrypt($modelId);
            $this->registerWorkflow($modelName, $this->modelId);
            $this->taxchange = BusinessTaxTypeChange::find($this->modelId);
            if (is_null($this->taxchange)) {
                abort(404);
            }
            $this->to_tax_type_id = $this->taxchange->to_tax_type_id;
            $this->from_tax_type_id = $this->taxchange->from_tax_type_id;
            $this->to_tax_type_currency = $this->taxchange->to_tax_type_currency;
            $this->taxTypes   = TaxType::select('id', 'name')->where('category', 'main')->get();
            $this->today = Carbon::today()->addDay()->format('Y-m-d');

            if ($this->taxchange->toTax && $this->taxchange->toTax->code == TaxType::VAT) {
                $this->subVatOptions = SubVat::all();
                $this->showSubVatOptions = true;
            }
        } catch (Exception $exception) {
            Log::error('TAX-TYPE-CHANGE-APPROVAL-MOUNT', [$exception]);
            abort(500, 'Something went wrong, please contact your system administrator for support.');
        }
        
    }

    public function updated($property)
    {
        if ($property === 'to_tax_type_id') { 
            $taxType = TaxType::findOrFail($this->to_tax_type_id);
            if ($taxType->code == TaxType::VAT) {
                $this->subVatOptions = SubVat::all();
                $this->showSubVatOptions = true;
            } else {
                $this->showSubVatOptions = false;
            }
        }

    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'effective_date' => 'required|strip_tag',
            'to_tax_type_currency' => 'required', 
            'to_tax_type_id' => 'required|numeric'
        ]);

        if ($this->to_tax_type_id == $this->from_tax_type_id) {
            $this->customAlert('warning', 'You cannot change to an existing tax type');
            return;
        }

        if ($this->showSubVatOptions) {
            $this->validate([
                'sub_vat_id' => 'required'
            ]);
        }

        DB::beginTransaction();
        try {
            if ($this->checkTransition('registration_manager_review')) {

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->effective_date = $this->effective_date;
                $this->subject->to_sub_vat_id = $this->sub_vat_id;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

                DB::commit();

                $notification_payload = [
                    'tax_change' => $this->taxchange,
                ];

                event(new SendMail('change-tax-type-approval', $notification_payload));
                event(new SendSms('change-tax-type-approval', $notification_payload));
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollback();
            Log::error('TAX-TYPE-CHANGE-APPROVE', [$e->getMessage()]);
            $this->customAlert('error', CustomMessage::error());
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error('TAX-TYPE-CHANGE-REJECT', [$e->getMessage()]);
            $this->customAlert('error', CustomMessage::error());
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
        return view('livewire.approval.taxtype-change');
    }
}
