<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentApproval;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Notifications\DatabaseNotification;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VerifyAction extends Component
{
    use LivewireAlert, PaymentsTrait;

    public $taxagent;

    protected $listeners = [
        'confirmed',
        'toggleStatus'
    ];

    public function approve()
    {
        $this->alert('success', 'Please add comment below to verify this request', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Verify',
            'onConfirmed' => 'toggleStatus',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'showLoaderOnConfirm' => true,
            'timer' => null,
            'input' => 'textarea',
            'data' => [
                'id' => $this->taxagent->id,
            ],
        ]);
    }

    public function reject()
    {
        $this->alert('warning', 'Please add comment below to reject this request', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'showLoaderOnConfirm' => true,
            'timer' => null,
            'input' => 'textarea',
            'data' => [
                'id' => $this->taxagent->id
            ],
        ]);
    }

    public function toggleStatus($value)
    {
        try {
            $comment = $value['value'];
            $data = (object)$value['data'];
            $agent = TaxAgent::findOrFail($data->id);
            $feeType = 'Registration Fee';
            //            todo: check if queried objects exist
            $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'duration', 'is_citizen', 'currency')
                ->where('category', $feeType)
                ->where('is_citizen', $agent->taxpayer->is_citizen)
                ->first();
            $amount = $fee->amount;
            $used_currency = $fee->currency;
            $tax_type = TaxType::query()->where('code', TaxType::TAX_CONSULTANT)->first();

            $billitems = [
                [
                    'billable_id' => $agent->id,
                    'billable_type' => get_class($agent),
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
                $this->generateTaxAgentRegControlNo($agent, $billitems, $comment, $feeType);
            } else {
                $this->alert('error', 'Invalid amount provided');
            }

            $agent->taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX CONSULTANT VERIFICATION',
                $message = 'Your application has been verified',
                $href = 'taxagent.apply',
                $hrefText = 'view'
            ));

            $this->flash('success', 'Request verified successfully');
            return redirect()->route('taxagents.requests');

        } catch (Exception $e) {
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong, please contact the administrator for help!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            redirect()->back()->getTargetUrl();
        }
    }


    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $comment = $value['value'];
            $data = (object)$value['data'];
//            todo: check if object exist
            $agent = TaxAgent::query()->find($data->id);
            $agent->status = TaxAgentStatus::REJECTED;
            $agent->verifier_reject_comment = $comment;
            $agent->verifier_id = Auth::id();
            $agent->first_rejected_at = now();
            $agent->save();

            if ($agent->status == TaxAgentStatus::CORRECTION)
            {
                $final = TaxAgentStatus::REJECTED;
                $initial = TaxAgentStatus::CORRECTION;
            }
            else
            {
                $final = TaxAgentStatus::REJECTED;
                $initial = TaxAgentStatus::PENDING;
            }

            $approval = new TaxAgentApproval();
            $approval->tax_agent_id = $agent->id;
            $approval->initial_status = $initial;
            $approval->destination_status = $final;
            $approval->comment = $comment;
            $approval->approved_by_id = Auth::id();
            $approval->approved_at = now();
            $approval->save();

//            todo: check if object exist
            $taxpayer = Taxpayer::query()->find($agent->taxpayer_id);
            $taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX-CONSULTANT VERIFICATION',
                $message = 'Your application has been rejected please correct and resubmit',
                $href = 'taxagent.apply',
                $hrefText = 'view'
            ));

            DB::commit();
            $this->flash('success', 'Request rejected successfully');
            return redirect()->route('taxagents.requests');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong, please contact the administrator for help!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            redirect()->back()->getTargetUrl();
        }
    }


    public function render()
    {
        return view('livewire.tax-agent.verify-action');
    }
}
