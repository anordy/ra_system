<?php

namespace App\Http\Livewire\TaxAgent;

use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
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
            $agent = TaxAgent::find($data->id);

            $taxpayer = Taxpayer::find($agent->taxpayer_id);

            $fee = TaPaymentConfiguration::where('category', 'registration fee')->first();
            $amount = $fee->amount;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billitems = [
                [
                    'billable_id' => $agent->id,
                    'billable_type' => get_class($agent),
                    'fee_id' => $fee->id,
                    'fee_type' => get_class($fee),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $amount,
                    'currency' => 'TZS',
                    'gfs_code' => '116101'
                ]
            ];
            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = 'Tax agent registration fee';
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = 'TZS';
            $createdby_type = get_class(User::find(Auth::id()));
            $exchange_rate = 0;
            $createdby_id = Auth::id();
            $payer_id = $taxpayer->id;

            $zmBill = ZmCore::createBill(
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
                $sendBill = ZmCore::sendBill($zmBill->id);
                if ($sendBill)
                {
                    $agent->status = TaxAgentStatus::VERIFIED;
                    $agent->verifier_true_comment = $comment;
                    $agent->verifier_id	 = Auth::id();
                    $agent->save();

                    $taxpayer->notify(new DatabaseNotification(
                        $subject = 'TAX-AGENT VERIFICATION',
                        $message = 'Your application has been verified',
                        $href = 'taxagent.apply',
                        $hrefText = 'view'
                    ));
                    DB::commit();
                    $this->flash('success', 'Request verified successfully');
                    return redirect()->route('taxagents.requests');
                }

                session()->flash('success', 'Zan Malipo Service is unavailable, try again later');
                DB::rollBack();
                $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
                redirect()->back()->getTargetUrl();

            } else {
                $agent->status = TaxAgentStatus::VERIFIED;
                $agent->verifier_true_comment = $comment;
                $agent->verifier_id	 = Auth::id();
                $agent->save();

                $taxpayer->notify(new DatabaseNotification(
                    $subject = 'TAX-AGENT VERIFICATION',
                    $message = 'Your application has been verified',
                    $href = 'taxagent.apply',
                    $hrefText = 'view'
                ));
                DB::commit();
                $this->flash('success', 'Request verified successfully');
                return redirect()->route('taxagents.requests');
            }

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
            $agent->status = TaxAgentStatus::REJECTED;
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
