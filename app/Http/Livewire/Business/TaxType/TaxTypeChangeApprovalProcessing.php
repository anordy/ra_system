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
    public $currencies = ['TZS', 'USD'];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->taxchange = BusinessTaxTypeChange::findOrFail($this->modelId);
        $this->taxTypes   = TaxType::where('category', 'main')->get();

        foreach (json_decode($this->taxchange->old_taxtype) as $value) {
            $this->oldTaxTypes[] = [
                'currency'    => $value->currency,
                'tax_type_id' => $value->tax_type_id,
            ];
        }

        foreach (json_decode($this->taxchange->new_taxtype) as $value) {
            $this->selectedTaxTypes[] = [
                'currency'    => $value->currency,
                'tax_type_id' => $value->tax_type_id,
                'business_id' => $value->business_id,
                'created_at' => $value->created_at
            ];
        }
    }

    public function getTaxNameById($taxId)
    {
        return TaxType::find($taxId)->name;
    }


    public function approve($transtion)
    {
        $this->validate(['comments' => 'required']);
        $business = Business::findOrFail($this->taxchange->business_id);

        DB::beginTransaction();
        try {
            if ($this->checkTransition('registration_manager_review')) {
                $business->taxTypes()->detach();

                DB::table('business_tax_type')->insert($this->selectedTaxTypes);

                $this->subject->status = BusinessStatus::APPROVED;

                $old_taxtypes_list = "";
                $new_taxtypes_list = "";
                $changed_tax_types = [];


                foreach ($this->oldTaxTypes as $key => $data) {
                    $old_taxtypes_list .= "{$this->getTaxNameById($data['tax_type_id'])}, ";
                    if ($data['tax_type_id'] !== $this->selectedTaxTypes[$key]['tax_type_id']) {
                        $changed_tax_types[] = [
                            'old' => $this->getTaxNameById($data['tax_type_id']),
                            'new' => $this->getTaxNameById($this->selectedTaxTypes[$key]['tax_type_id']),
                            'new_tax_id' => $this->selectedTaxTypes[$key]['tax_type_id']
                        ];
                    }
                }

                foreach ($business->taxTypes as $type) {
                    $new_taxtypes_list .= "{$type->name}, ";
                }

                $notification_payload = [
                    'old_taxtypes' => $old_taxtypes_list,
                    'new_taxtypes' => $new_taxtypes_list,
                    'new_taxes' => $changed_tax_types,
                    'business' => $business,
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
