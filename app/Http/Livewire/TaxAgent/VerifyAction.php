<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\ExchangeRate;
use App\Models\Returns\ReturnStatus;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\BillingStatus;
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
            'timer' => null,
            'input' => 'textarea',
            'data' => [
                'id' => $this->taxagent->id
            ],
        ]);
    }

    public function toggleStatus($value)
    {
        DB::beginTransaction();
        try {
            $comment = $value['value'];
            $data = (object)$value['data'];
            $agent = TaxAgent::query()->find($data->id);

            $taxpayer = Taxpayer::query()->find($agent->taxpayer_id);

            $fee = TaPaymentConfiguration::query()->where('category', 'registration fee')->first();
            $amount = $fee->amount;
            $used_currency = $fee->currency;

            if ($used_currency != 'TZS')
            {
                $rate = ExchangeRate::query()->where('currency', $used_currency)
                    ->first();
                $rate = $rate->mean;
            }
            else{
                $rate = 1;
            }

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

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = 'Tax Consultant Registration Fee';
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = $used_currency;
            $createdby_type = get_class(User::query()->findOrFail(Auth::id()));
            $exchange_rate = $rate;
            $createdby_id = Auth::id();
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billableId = $agent->id;
            $billableType = get_class($agent);


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
                    $agent->status = TaxAgentStatus::VERIFIED;
                    $agent->billing_status = BillingStatus::CN_GENERATING;
                    $agent->save();
                    $taxpayer->notify(new DatabaseNotification(
                        $subject = 'TAX CONSULTANT VERIFICATION',
                        $message = 'Your application has been verified',
                        $href = 'taxagent.apply',
                        $hrefText = 'view'
                    ));
                    $this->alert('success', 'Request verified successfully.');
                } else {
                    session()->flash('error', 'Control number generation failed, try again later');
                    $agent->billing_status = BillingStatus::CN_GENERATION_FAILED;
                }

                $agent->save();

            } else {
                // We are local
                $agent->status = TaxAgentStatus::VERIFIED;
                $agent->billing_status = BillingStatus::CN_GENERATED;
                $agent->save();

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
            $this->flash('success', 'Request verified successfully');
            return redirect()->route('taxagents.requests');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            redirect()->back()->getTargetUrl();
        }
    }


    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $comment = $value['value'];
            $data = (object)$value['data'];
            $agent = TaxAgent::find($data->id);
            $agent->status = BillingStatus::REJECTED;
            $agent->verifier_reject_comment = $comment;
            $agent->verifier_id	 = Auth::id();
            $agent->save();

            $taxpayer = Taxpayer::query()->find($agent->taxpayer_id);
            $taxpayer->notify(new DatabaseNotification(
                $subject = 'TAX-AGENT VERIFICATION',
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
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
            redirect()->back()->getTargetUrl();
        }
    }


    public function render()
    {
        return view('livewire.tax-agent.verify-action');
    }
}
