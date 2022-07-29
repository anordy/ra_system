<?php

namespace App\Http\Livewire\Business\TaxType;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\BusinessStatus;
use Illuminate\Support\Facades\DB;
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


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->taxchange = BusinessTaxTypeChange::findOrFail($this->modelId);
    }


    public function approve($transtion)
    {
        $business = Business::findOrFail($this->taxchange->business_id);

        try {
            if ($this->checkTransition('registration_manager_review')) {
                $business->taxTypes()->detach();

                $old_taxtypes = json_decode($this->taxchange->old_taxtype);
                $new_taxtypes = json_decode($this->taxchange->new_taxtype, true);

                DB::table('business_tax_type')->insert($new_taxtypes);

                $this->subject->status = BusinessStatus::APPROVED;

                $old_taxtypes_list = "";
                $new_taxtypes_list = "";

                foreach ($old_taxtypes as $data) {
                    $old_taxtypes_list .= "{$data->name}, ";
                }

                foreach ($business->taxTypes as $type) {
                    $new_taxtypes_list .= "{$type->name}, ";
                }

                $notification_payload = [
                    'old_taxtypes' => $old_taxtypes_list,
                    'new_taxtypes' => $new_taxtypes_list,
                    'business' => $business,
                    'time' => Carbon::now()->format('d-m-Y')
                ];

                event(new SendMail('change-tax-type-approval', $notification_payload));
                event(new SendSms('change-tax-type-approval', $notification_payload));
                
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        try {
            if ($this->checkTransition('registration_manager_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.approval.taxtype-change');
    }
}
