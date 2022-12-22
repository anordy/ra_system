<?php

namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Models\BillingStatus;
use App\Models\BusinessTaxType;
use App\Models\ExchangeRate;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\ReturnStatus;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgentApproval;
use App\Models\TaxAgentStatus;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxType;
use App\Models\TransactionFee;
use App\Models\ZmBill;
use App\Models\ZmBillChange;
use App\Services\Api\ZanMalipoInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait PaymentsTrait
{
    /**
     * @param ZmBill $bill
     * @return boolean
     */
    public function regenerateControlNo(ZmBill $bill): bool
    {
        DB::beginTransaction();

        try {
            $return = $bill->billable;

            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                // We are local
                $return->status = ReturnStatus::CN_GENERATED;
                $return->save();

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status       = 'pending';
                $bill->control_number   = rand(2000070001000, 2000070009999);
                $bill->save();
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return false;
        }
    }

    /**
     * @param $return
     * @param $billItems
     * @return void
     * @throws \DOMException
     */
    public function generateControlNo($return, $billItems)
    {
        $taxpayer      = $return->taxpayer;
        $tax_type      = BusinessTaxType::where('tax_type_id', $return->tax_type_id)->first();
        $exchange_rate = 1;

        if ($tax_type->currency != 'TZS') {
            $bot_rate      = ExchangeRate::where('currency', $tax_type->currency)->where('business_id', $return->business_id)->first();
            $exchange_rate = $bot_rate->mean;
        }

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "Return payment for {$return->business->name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $tax_type->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $return->id;
        $billableType   = get_class($return);

        $bill = ZmCore::createBill(
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $return->status = ReturnStatus::CN_GENERATED;
            $return->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            $this->flash('success', 'Your return was submitted, you will receive your payment information shortly - test');
        }
    }

    public function cancelBill(ZmBill $bill, $cancellationReason)
    {
        if (config('app.env') != 'local') {
            $cancelBill = (new ZanMalipoInternalService)->cancelBill($bill, $cancellationReason);

            return $cancelBill;
        } else {
            $bill->status              = PaymentStatus::CANCELLED;
            $bill->cancellation_reason = $cancellationReason ?? '';
            $bill->save();
        }
    }

    public function updateBill(ZmBill $bill, $expireDate)
    {
        if (!($expireDate instanceof Carbon)) {
            $expireDate = Carbon::make($expireDate);
        }
        if (config('app.env') != 'local') {
            $updateBill = (new ZanMalipoInternalService)->updateBill($bill, $expireDate->toDateTimeString());

            return $updateBill;
        } else {
            $bill->expire_date = $expireDate->toDateTimeString();
            $bill->save();

            $bill_change = ZmBillChange::create([
                'zm_bill_id'  => $bill->id,
                'expire_date' => Carbon::parse($expireDate)->toDateTimeString(),
                'category'    => 'update',
                'staff_id'    => Auth::id(),
                'ack_date'    => Carbon::now()->toDateTimeString(),
                'ack_status'  => ZmResponse::SUCCESS,
            ]);
        }
    }

    public function landLeaseGenerateControlNo($leasePayment, $billItems)
    {
        $taxpayer       = $leasePayment->taxpayer;
        $tax_type       = TaxType::where('code', 'land-lease')->first();
        $exchange_rate  = ExchangeRate::where('currency', 'USD')->first()->mean;

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "Payment for Land Lease with DP number {$leasePayment->landLease->dp_number}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = 'USD';
        $createdby_type = get_class(Auth::user());
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $leasePayment->id;
        $billableType   = get_class($leasePayment);

        $bill = ZmCore::createBill(
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $createBill = (new ZanMalipoInternalService)->createBill($bill);
        } else {
            // We are local
            $leasePayment->status = LeaseStatus::CN_GENERATED;
            $leasePayment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }
    }

    /**
     * @param $amount
     * @param $currency
     * @param $exchangeRate
     * @return float|int|void
     * @throws \Exception
     */
    public function getTransactionFee($amount, $currency, $exchangeRate = null)
    {
        if ($currency != 'TZS' && (!is_numeric($exchangeRate) || $exchangeRate <= 0)) {
            throw new \Exception('Please provide exchange rate for non TZS currency');
        }

        if ($currency != 'TZS') {
            $amount = $amount * $exchangeRate;
        }

        $eGafee = TransactionFee::whereBetween('minimum_amount', [$amount, 'maximum_amount'])->pluck('fee')->get();
        dd($eGafee);

        switch ($amount) {
            case $amount >= 0.00 && $amount <= 100000.00:
                $fee = $amount * 0.025;

                break;
            case $amount >= 100001.00 && $amount <= 500000.00:
                $fee = $amount * 0.02;

                break;
            case $amount >= 500001.00 && $amount <= 1000000.00:
                $fee = $amount * 0.013;

                break;
            case $amount >= 1000001.00 && $amount <= 5000000.00:
                $fee = $amount * 0.003;

                break;
            case $amount >= 5000001.00 && $amount <= 10000000.00:
                $fee = $amount * 0.0015;

                break;
            case $amount >= 10000001.00:
                $fee = 20000;

                break;
            default:
                throw new \Exception('Amount out of range.');
        }

        if ($currency != 'TZS') {
            $fee = round($fee / $exchangeRate, 2);
        }

        return $fee;
    }

    /**
     * @param $bill
     * @param $billable
     * @return void
     * @throws \DOMException
     */
    public function sendBill($bill, $billable)
    {
        if (config('app.env') != 'local') {
            $response = ZmCore::sendBill($bill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                $billable->status = BillStatus::CN_GENERATING;
                $billable->save();

                session()->flash('success', 'Your request was submitted, you will receive your payment information shortly.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
                $billable->status = BillStatus::CN_GENERATION_FAILED;
            }

            $billable->save();
        } else {
            // We are local
            $billable->status = BillStatus::CN_GENERATED;
            $billable->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = rand(2000070001000, 2000070009999);
            $bill->save();

            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly - test');
        }
    }

    public function generateDebtControlNo($debt)
    {
        $taxTypes = TaxType::all();

        $tax_type = TaxType::findOrFail($debt->tax_type_id);

        if ($debt->principal > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->principal,
                'currency'            => $debt->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        if ($debt->penalty > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->penalty,
                'currency'            => $debt->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::PENALTY)->first()->id,
            ];
        }

        if ($debt->interest > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->interest,
                'currency'            => $debt->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::INTEREST)->first()->id,
            ];
        }

        $taxpayer = $debt->business->taxpayer;

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "{$debt->taxtype->name} Debt Payment for {$debt->business->name} {$debt->location->name}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate  = $debt->currency == 'TZS' ? 1 : ExchangeRate::where('currency', $debt->currency)->first()->mean;
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $debt->id;
        $billableType   = get_class($debt);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $debt->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateAssessmentDebtControlNo($debt)
    {
        $taxTypes = TaxType::all();

        $tax_type = TaxType::findOrFail($debt->tax_type_id);

        if ($debt->principal_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->principal_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        if ($debt->penalty_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->penalty_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::PENALTY)->first()->id,
            ];
        }

        if ($debt->interest_amount > 0) {
            $billItems[] = [
                'billable_id'         => $debt->id,
                'billable_type'       => get_class($debt),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $debt->interest_amount,
                'currency'            => $debt->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::INTEREST)->first()->id,
            ];
        }

        $taxpayer = $debt->business->taxpayer;

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "{$debt->taxtype->name} Debt Payment for {$debt->business->name} {$debt->location->name}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $debt->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate  = $debt->currency == 'TZS' ? 1 : ExchangeRate::where('currency', $debt->currency)->first()->mean;
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $debt->id;
        $billableType   = get_class($debt);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $debt->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $debt->payment_status = ReturnStatus::CN_GENERATED;

            $debt->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateWaivedAssessmentDisputeControlNo($assessment)
    {
        $taxTypes = TaxType::all();

        $tax_type = TaxType::findOrFail($assessment->tax_type_id);

        if ($assessment->principal_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->principal_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $tax_type->gfs_code,
                'tax_type_id'         => $tax_type->id,
            ];
        }

        if ($assessment->penalty_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->penalty_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::PENALTY)->first()->id,
            ];
        }

        if ($assessment->interest_amount > 0) {
            $billItems[] = [
                'billable_id'         => $assessment->id,
                'billable_type'       => get_class($assessment),
                'use_item_ref_on_pay' => 'N',
                'amount'              => $assessment->interest_amount,
                'currency'            => $assessment->currency,
                'gfs_code'            => $taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
                'tax_type_id'         => $taxTypes->where('code', TaxType::INTEREST)->first()->id,
            ];
        }

        $taxpayer = $assessment->business->taxpayer;

        if ($assessment->assessment_type == TaxAudit::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->taxAuditLocationNames() ?? 'Multiple business locations';
        } elseif ($assessment->assessment_type == TaxInvestigation::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->taxInvestigationLocationNames() ?? 'Multiple business locations';
        } elseif ($assessment->assessment_type == TaxVerification::class) {
            $assessmentLocations = $assessment->assessment_type::find($assessment->assessment_id)->location->name ?? 'Multiple business locations';
        } else {
            $assessmentLocations = 'Business location';
        }
        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "{$assessment->taxtype->name} dispute waiver for {$assessment->business->name} in {$assessmentLocations}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $assessment->currency;
        $createdby_type = Auth::user() != null ? get_class(Auth::user()) : null;
        $createdby_id   = Auth::id() != null ? Auth::id() : null;
        $exchange_rate  = $assessment->currency == 'TZS' ? 1 : ExchangeRate::where('currency', $assessment->currency)->first()->mean;
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $assessment->id;
        $billableType   = get_class($assessment);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $assessment->tax_type_id,
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
            $billItems
        );

        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $assessment->payment_status = ReturnStatus::CN_GENERATED;

            $assessment->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateTaxAgentRegControlNo($agent, $billitems, $comment)
    {
        $exchange_rate = 1;
        $tax_type      = TaxType::query()->where('code', TaxType::TAX_CONSULTANT)->first();

        $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'duration', 'is_citizen', 'currency')
                    ->where('category', 'Registration Fee')
                    ->where('is_citizen', $agent->taxpayer->is_citizen)
                    ->first();
                    
        $used_currency = $fee->currency;

        if ($tax_type->currency != 'TZS') {
            $exchange_rate = ExchangeRate::query()->where('currency', $used_currency)->latest()->first()->mean;
        }

        $taxpayer       = $agent->taxpayer;
        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = 'Tax Consultant Registration Fee';
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $used_currency;
        $createdby_type = get_class(Auth::user());
        $exchange_rate  = $exchange_rate;
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $agent->id;
        $billableType   = get_class($agent);

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
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $agent->status                = TaxAgentStatus::VERIFIED;
            $agent->billing_status        = BillingStatus::CN_GENERATED;
            $agent->verifier_id           = Auth::id();
            $agent->verifier_true_comment = $comment;
            $agent->verified_at           = now();
            $agent->save();

            if ($agent->status == TaxAgentStatus::CORRECTION) {
                $final   = TaxAgentStatus::VERIFIED;
                $initial = TaxAgentStatus::CORRECTION;
            } else {
                $final   = TaxAgentStatus::VERIFIED;
                $initial = TaxAgentStatus::PENDING;
            }
            $approval                     = new TaxAgentApproval();
            $approval->tax_agent_id       = $agent->id;
            $approval->initial_status     = $initial;
            $approval->destination_status = $final;
            $approval->comment            = $comment;
            $approval->approved_by_id     = Auth::id();
            $approval->approved_at        = now();
            $approval->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

    public function generateTaxAgentRenewControlNo($req, $billitems, $comment)
    {
        $exchange_rate = 1;
        $tax_type      = TaxType::query()->where('code', TaxType::TAX_CONSULTANT)->first();

        $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'duration', 'is_citizen', 'currency')
                    ->where('category', 'Renewal Fee')
                    ->where('is_citizen', $req->tax_agent->taxpayer->is_citizen)
                    ->first();
                    
        $used_currency = $fee->currency;

        if ($tax_type->currency != 'TZS') {
            $exchange_rate = ExchangeRate::query()->where('currency', $used_currency)->latest()->first()->mean;
        }

        $taxpayer       = $req->tax_agent->taxpayer;
        $payer_type     = get_class($req->tax_agent->taxpayer);
        $payer_name     = implode(' ', [$req->tax_agent->taxpayer->first_name, $req->tax_agent->taxpayer->last_name]);
        $payer_email    = $req->tax_agent->taxpayer->email;
        $payer_phone    = $req->tax_agent->taxpayer->mobile;
        $description    = 'Tax Consultant Renewal Fee';
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = $used_currency;
        $createdby_type = get_class(Auth::user());
        $exchange_rate  = $exchange_rate;
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $req->id;
        $billableType   = get_class($req);

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
            $sendBill = (new ZanMalipoInternalService)->createBill($zmBill);
        } else {
            // We are local
            $req->billing_status = BillingStatus::CN_GENERATING;
            $req->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status       = 'pending';
            $zmBill->control_number   = rand(2000070001000, 2000070009999);
            $zmBill->save();
        }
    }

        /**
     * @param $return
     * @param $billItems
     * @return void
     * @throws \DOMException
     */
    public function generateGeneralControlNumber($bill)
    {
        if (config('app.env') != 'local') {
            $sendBill = (new ZanMalipoInternalService)->createBill($bill);
        }
    }
}
