<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\ExchangeRate;
use App\Models\RenewTaxAgentRequest;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Notifications\DatabaseNotification;
use App\Traits\ExchangeRateTrait;
use App\Traits\PaymentsTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VerifyAction extends Component
{
    use LivewireAlert, PaymentsTrait, ExchangeRateTrait;

    public $renew;

    protected $listeners = [
        'confirmed',
        'toggleStatus'
    ];

    public function approve()
    {
        $this->alert('warning', 'Are you sure you want to approve this request ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'toggleStatus',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $this->renew->id
            ],

        ]);
    }

    public function reject()
    {
        $this->alert('warning', 'Are you sure you want to approve this request ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $this->renew->id
            ],

        ]);
    }

    public function toggleStatus($value)
    {
        try {
            $data = (object)$value['data'];
            $req = RenewTaxAgentRequest::query()->find($data->id);
            $req->status = TaxAgentStatus::VERIFIED;
            $req->app_true_comment = 'The request is valid';
            $req->approved_by_id = Auth::id();
            $req->approved_at = now();
            $req->save();

            $taxpayer = Taxpayer::query()->find($req->tax_agent->taxpayer_id);

            $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'is_citizen', 'currency')
                ->where('category', 'Renewal Fee')
                ->where('is_citizen', $taxpayer->is_citizen)
                ->first();
            $amount = $fee->amount;
            $used_currency = $fee->currency;

            $tax_type = TaxType::query()->where('code', TaxType::TAX_CONSULTANT)->first();
            $billitems = [
                [
                    'billable_id' => $req->id,
                    'billable_type' => get_class($req),
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
                $this->generateTaxAgentRenewControlNo($req, $billitems, '');
            } else {
                $this->alert('error', 'Invalid amount provided');
            }

            $this->flash('success', 'saved successfully');
            return redirect()->route('taxagents.renew');

        } catch (Exception $e) {
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong, Could you please contact our administrator for assistance?!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $req = RenewTaxAgentRequest::query()->find($data->id);
            $req->status = TaxAgentStatus::REJECTED;
            $req->app_reject_comment = 'The request is invalid';
            $req->rejected_by_id = Auth::id();
            $req->rejected_at = now();
            $req->save();

            $taxpayer = Taxpayer::query()->find($req->tax_agent->id);
            $taxpayer->notify(new DatabaseNotification(
                $message = 'Tax agent renew ',
                $type = 'info',
                $messageLong = 'Your request for tax agent renew has been rejected',
                $href = '/taxagent/apply',
                $hrefText = 'View'
            ));

//			event(new SendMail('tax-agent-renew-approval', $req->tax_agent->taxpayer_id));
//			event(new SendSms('tax-agent-renew-approval', $req->tax_agent->taxpayer_id));

            DB::commit();
            $this->flash('success', 'Request rejected successfully');
            return redirect()->route('taxagents.renew');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong, Could you please contact our administrator for assistance?!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.tax-agent.renew.verify-action');
    }
}
