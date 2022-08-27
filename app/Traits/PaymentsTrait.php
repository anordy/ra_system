<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\ZmBill;
use App\Models\TaxType;
use App\Enum\BillStatus;
use App\Models\Debts\Debt;
use App\Enum\PaymentStatus;
use App\Models\ExchangeRate;
use App\Models\BusinessTaxType;
use App\Services\ZanMalipo\ZmCore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\ZanMalipo\ZmResponse;

trait PaymentsTrait {

    /**
     * @param ZmBill $bill
     * @return boolean
     */
    public function regenerateControlNo(ZmBill $bill): bool
    {
        DB::beginTransaction();
        try {
            $return = $bill->billable;
            $response = ZmCore::sendBill($bill);
            if (config('app.env') != 'local') {
                if ($response->status === ZmResponse::SUCCESS)
                {
                    $return->status = ReturnStatus::CN_GENERATING;
                } else {
                    $return->status = ReturnStatus::CN_GENERATION_FAILED;
                }
                $return->save();
            } else {
                // We are local
                $return->status = ReturnStatus::CN_GENERATED;
                $return->save();

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = 'pending';
                $bill->control_number = '90909919991909';
                $bill->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $e){
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
    public function generateControlNo($return, $billItems){
        $taxpayer = $return->taxpayer;
        $tax_type = BusinessTaxType::where('tax_type_id', $return->tax_type_id)->first();
        $exchange_rate = 1;

        if ($tax_type->currency !== 'TZS') {
            $bot_rate = ExchangeRate::where('currency', $tax_type->currency)->first();
            $exchange_rate = $bot_rate->mean;
        }

        $payer_type = get_class($taxpayer);
        $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
        $payer_email = $taxpayer->email;
        $payer_phone = $taxpayer->mobile;
        $description = "Return payment for {$return->business->name} - {$return->financialMonth->name} {$return->financialMonth->year->code}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency = $tax_type->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $taxpayer->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $return->id;
        $billableType = get_class($return);

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
            $response = ZmCore::sendBill($bill->id);
            if ($response->status === ZmResponse::SUCCESS)
            {
                $return->status = ReturnStatus::CN_GENERATING;
                $return->save();

                $this->flash('success', 'Your return was submitted, you will receive your payment information shortly.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
                $return->status = ReturnStatus::CN_GENERATION_FAILED;
            }

            $return->save();

        } else {
            // We are local
            $return->status = ReturnStatus::CN_GENERATED;
            $return->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = '90909919991909';
            $bill->save();

            $this->flash('success', 'Your return was submitted, you will receive your payment information shortly - test');
        }
    }


    public function cancelBill(ZmBill $bill, $cancellationReason){
        if (config('app.env') != 'local') {
            ZmCore::cancelBill($bill->id, $cancellationReason); // Works ?
        } else {
            $bill->status = PaymentStatus::CANCELLED;
            $bill->cancellation_reason = $cancellationReason ?? '';
            $bill->save();
        }
    }

    public function landLeaseGenerateControlNo($landLease, $billItems)
    {
        $taxpayer      = $landLease->taxpayer;
        $tax_type      = TaxType::where('code','land-lease')->first();
        $exchange_rate  = ExchangeRate::where('currency', 'USD')->first()->mean; 

        $payer_type     = get_class($taxpayer);
        $payer_name     = implode(' ', [$taxpayer->first_name, $taxpayer->last_name]);
        $payer_email    = $taxpayer->email;
        $payer_phone    = $taxpayer->mobile;
        $description    = "Payment for Land Lease with DP number {$landLease->dp_number}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency       = "USD";
        $createdby_type = get_class(Auth::user());
        $createdby_id   = Auth::id();
        $payer_id       = $taxpayer->id;
        $expire_date    = Carbon::now()->addMonth()->toDateTimeString();
        $billableId     = $landLease->id;
        $billableType   = get_class($landLease);

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
            $response = ZmCore::sendBill($bill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                $landLease->status = ReturnStatus::CN_GENERATING;
                $landLease->save();

                $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly.');
            } else {
                session()->flash('error', 'Control number generation failed, try again later');
                $landLease->status = ReturnStatus::CN_GENERATION_FAILED;
            }

            $landLease->save();
        } else {
            // We are local
            $landLease->status = ReturnStatus::CN_GENERATED;
            $landLease->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status       = 'pending';
            $bill->control_number   = '90909919991909';
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
    public function getTransactionFee($amount, $currency, $exchangeRate = null){
        if ($currency != 'TZS' && (!is_numeric($exchangeRate) || $exchangeRate <= 0)){
            throw new \Exception('Please provide exchange rate for non TZS currency');
        }

        if ($currency != 'TZS'){
            $amount = $amount * $exchangeRate;
        }

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
    public function sendBill($bill, $billable){
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
            $bill->control_number   = '90909919991909';
            $bill->save();

            session()->flash('success', 'Your request was submitted, you will receive your payment information shortly - test');
        }
    }

    public function generateDebtControlNo($debt)
    {
        $taxTypes = TaxType::all();
        $billItems[] = [
            'billable_id' => $debt->id,
            'billable_type' => get_class($debt),
            'use_item_ref_on_pay' => 'N',
            'amount' => $debt->principal_amount,
            'currency' => $debt->currency,
            'gfs_code' => $taxTypes->where('code', TaxType::DEBTS)->first()->gfs_code,
            'tax_type_id' => $taxTypes->where('code', TaxType::DEBTS)->first()->id
        ];

        $billItems[] = [
            'billable_id' => $debt->id,
            'billable_type' => get_class($debt),
            'use_item_ref_on_pay' => 'N',
            'amount' => $debt->penalty,
            'currency' => $debt->currency,
            'gfs_code' => $taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
            'tax_type_id' => $taxTypes->where('code', TaxType::PENALTY)->first()->id
        ];

        $billItems[] = [
            'billable_id' => $debt->id,
            'billable_type' => get_class($debt),
            'use_item_ref_on_pay' => 'N',
            'amount' => $debt->interest,
            'currency' => $debt->currency,
            'gfs_code' => $taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
            'tax_type_id' => $taxTypes->where('code', TaxType::INTEREST)->first()->id
        ];

        $taxpayer = $debt->business->taxpayer;

        $payer_type = get_class($taxpayer);
        $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
        $payer_email = $taxpayer->email;
        $payer_phone = $taxpayer->mobile;
        $description = "{$debt->debt->taxtype->name} Debt for {$debt->debt->business->name} {$debt->location->name}";
        $payment_option = ZmCore::PAYMENT_OPTION_FULL;
        $currency = $debt->currency;
        $createdby_type = 'Job';
        $createdby_id = null;
        $exchange_rate = $debt->currency == 'TZS' ? 1 : ExchangeRate::where('currency', $debt->currency)->first()->mean;
        $payer_id = $taxpayer->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $debt->id;
        $billableType = get_class($debt);

        $zmBill = ZmCore::createBill(
            $billableId,
            $billableType,
            $taxTypes->where('code', TaxType::DEBTS)->first()->id,
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
            $response = ZmCore::sendBill($zmBill->id);
            if ($response->status === ZmResponse::SUCCESS) {
                $debt->status = ReturnStatus::CN_GENERATING;
                $debt->debt->return_category = 'debt';

                $debt->save();
                $debt->debt->save();

            } else {
                $debt->status = ReturnStatus::CN_GENERATION_FAILED;
            }

            $debt->save();
        } else {

            // We are local
            $debt->status = ReturnStatus::CN_GENERATED;
            $debt->debt->return_category = 'debt';

            $debt->save();
            $debt->debt->save();

            // Simulate successful control no generation
            $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $zmBill->zan_status = 'pending';
            $zmBill->control_number = '90909919991909';
            $zmBill->save();

        }
    }
}