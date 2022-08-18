<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\BillingStatus;
use App\Models\ExchangeRate;
use App\Models\RenewTaxAgentRequest;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VerifyAction extends Component
{
    use LivewireAlert;

    public $agent;

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
                'id' => $this->agent->request->id
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
                'id' => $this->agent->request->id
            ],

        ]);
    }

    public function toggleStatus($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $req = RenewTaxAgentRequest::query()->find($data->id);
            $req->status = TaxAgentStatus::VERIFIED;
            $req->app_true_comment = 'The request is valid';
            $req->approved_by_id = Auth::id();
            $req->approved_at = now();
            $req->renew_first_date = Carbon::now();
            $req->renew_expire_date = Carbon::now()->addYear()->toDateTimeString();
            $req->save();

            $taxpayer = Taxpayer::query()->find($req->tax_agent->taxpayer_id);

            $fee = TaPaymentConfiguration::query()->where('category', 'renewal fee')->first();
            $amount = $fee->amount;
            $used_currency = $fee->currency;
            if ($used_currency != 'TZS') {
                $rate = ExchangeRate::query()->where('currency', $used_currency)
                    ->first();
                $rate = $rate->mean;
            } else {
                $rate = 1;
            }

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

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = 'Tax Consultant Renewal Fee';
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = $used_currency;
            $createdby_type = get_class(User::query()->findOrFail(Auth::id()));
            $exchange_rate = $rate;
            $createdby_id = Auth::id();
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billableId = $req->id;
            $billableType = get_class($req);

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $tax_type->id,
                $payer_id,
                $payer_type,
                $payer_name,
                $payer_email,
                $payer_phone,
                $expire_date,
                $description,
                $payment_option,
                $currency,
                $exchange_rate,
                $createdby_id,
                $createdby_type,
                $billitems
            );

            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    $req->billing_status = BillingStatus::CN_GENERATING;
                    $req->save();
                    $taxpayer->notify(new DatabaseNotification(
                        $subject = 'TAX CONSULTANT VERIFICATION',
                        $message = 'Your application has been verified',
                        $href = 'taxagent.apply',
                        $hrefText = 'view'
                    ));
                    $this->alert('success', 'Request verified successfully.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                    $req->status = BillingStatus::CN_GENERATION_FAILED;
                }
                $req->save();

            } else {
                // We are local
                $req->billing_status = BillingStatus::CN_GENERATED;
                $req->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = '90909919991909';
                $zmBill->save();

                $taxpayer->notify(new DatabaseNotification(
                    $subject = 'TAX CONSULTANT VERIFICATION',
                    $message = 'Your application has been verified',
                    $href = 'taxagent.apply',
                    $hrefText = 'view'
                ));
            }
            DB::commit();
            $this->flash('success', 'saved successfully');
            return redirect()->route('taxagents.renew');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
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
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.tax-agent.renew.verify-action');
    }
}
