<?php

namespace App\Jobs\NonTaxResident;

use App\Enum\GeneralConstant;
use App\Models\Currency;
use App\Models\Returns\ReturnStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Services\Api\ZanMalipoInternalService;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\ExchangeRateTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateNtrControlNo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ExchangeRateTrait;

    public $ntrReturn;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ntrReturn)
    {
        $this->ntrReturn = $ntrReturn;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->generateControlNumber($this->ntrReturn);
    }


    public function generateControlNumber($return)
    {
        try {
            $returnId = $return->id;
            $returnClass = get_class($return);

            $billItems = [];

            $taxType = $return->taxType;

            if ($return->principal > 0) {
                $billItems[] = [
                    'billable_id' => $returnId,
                    'billable_type' => $returnClass,
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $return->penalty,
                    'currency' => $return->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $return->tax_type_id
                ];
            }
            if ($return->penalty > 0) {
                $billItems[] = [
                    'billable_id' => $returnId,
                    'billable_type' => $returnClass,
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $return->penalty,
                    'currency' => $return->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => TaxType::where('code', TaxType::PENALTY)->firstOrFail()->id
                ];
            }
            if ($return->interest > 0) {
                $billItems[] = [
                    'billable_id' => $returnId,
                    'billable_type' => $returnClass,
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $return->interest,
                    'currency' => $return->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => TaxType::where('code', TaxType::INTEREST)->firstOrFail()->id
                ];
            }

            if (count($billItems) == GeneralConstant::ZERO_INT) {
                throw new Exception('No bill items found on ntr bill');
            }


            $payer_name = $return->business->name;
            $payer_email = $return->ntrBusiness->email;
            $description = "Payment for Vat Electronic Service of {$payer_name} for financial month {$return->month->name} {$return->year->name}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = Currency::USD;
            $expire_date = $return->curr_payment_due_date;
            $billableId = $return->id;
            $billableType = get_class($return);

            $exchangeRate = $this->getExchangeRate(Currency::USD);

            $bill = ZmCore::createBill(
                $billableId,
                $billableType,
                $return->tax_type_id,
                $return->business_id,
                Taxpayer::class,
                $payer_name,
                $payer_email,
                null,
                $expire_date,
                $description,
                $payment_option,
                $currency,
                $exchangeRate,
                null,
                null,
                $billItems
            );

            if (config('app.env') != 'local') {
                $sendBill = (new ZanMalipoInternalService)->createBill($bill);
            } else {
                $return->payment_status = ReturnStatus::CN_GENERATED;
                if (!$return->save()) throw new Exception('Failed to save return payment status');

                // Simulate successful control no generation
                $bill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $bill->zan_status = GeneralConstant::PENDING;
                $bill->control_number = random_int(2000070001000, 2000070009999);
                $bill->save();
            }
        } catch (Exception $exception) {
            Log::error('GENERATE-NTR-CONTROL-NUMBER-JOB', [$exception]);
            throw $exception;
        }
    }

}
