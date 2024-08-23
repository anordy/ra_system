<?php

namespace App\Traits;



use App\Enum\LeaseStatus;
use App\Models\TaxType;
use App\Services\Api\ZanMalipoInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

trait OffencePaymentTrait
{
    public function offenceGenerateControlNo($offencePayment, $billItems)
    {
        $taxpayer = $offencePayment->name;
        $tax_type = $offencePayment->tax_type;
        $exchange_rate = $this->getExchangeRate($offencePayment->currency);


        $payer_type = get_class($offencePayment);
        $payer_name = $taxpayer;
        $payer_email = null;
        $payer_phone = $offencePayment->mobile;
        $description = "Payment for Offence";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $offencePayment->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $offencePayment->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $offencePayment->id;
        $billableType = get_class($offencePayment);

        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $tax_type,
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
            $offencePayment->status = LeaseStatus::CN_GENERATED;
            $offencePayment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }

        return true;
    }
    public function offenceGenerateBill($offencePayment)
    {
        $taxpayer = $offencePayment->name;
        $tax_type = $offencePayment->tax_type;
        $exchange_rate = $this->getExchangeRate($offencePayment->currency);

        $payer_type = get_class($offencePayment);
        $payer_name = $taxpayer;
        $payer_email = null;
        $payer_phone = $offencePayment->mobile;
        $description = "Payment for Offence";
        $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
        $currency = $offencePayment->currency;
        $createdby_type = get_class(Auth::user());
        $createdby_id = Auth::id();
        $payer_id = $offencePayment->id;
        $expire_date = Carbon::now()->addMonth()->toDateTimeString();
        $billableId = $offencePayment->id;
        $billableType = get_class($offencePayment);

        $billItems = [
            [
                'billable_id' => $offencePayment->id,
                'billable_type' => get_class($offencePayment),
                'use_item_ref_on_pay' => 'N',
                'amount' => roundOff($offencePayment->amount, $offencePayment->currency),
                'currency' => $offencePayment->currency,
                'gfs_code' => TaxType::findOrFail($offencePayment->tax_type)->gfs_code,
                'tax_type_id' => $offencePayment->tax_type
            ],
        ];


        $bill = ZmCore::createBill(
            $billableId,
            $billableType,
            $tax_type,
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
            $offencePayment->status = LeaseStatus::CN_GENERATED;
            $offencePayment->save();

            // Simulate successful control no generation
            $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
            $bill->zan_status = 'pending';
            $bill->control_number = random_int(2000070001000, 2000070009999);
            $bill->save();

            // $this->flash('success', 'Your landLease was submitted, you will receive your payment information shortly - test');
        }
    }
}