<?php

namespace App\Traits;

use App\Models\Returns\ReturnStatus;
use App\Models\ZmBill;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function getTransactionFee($amount, $currency, $exchangeRate = null){
        if ($currency != 'TZS' && $exchangeRate == null){
            throw new \Exception('Please provide exchange rate for non TZS currency');
        }

        if ($currency != 'TZS'){
            $amount = $amount * $exchangeRate;
        }

        switch ($amount) {
            case $amount >= 0.00 && $amount <= 100000.00:
                return $amount * 0.025;
                break;
            case $amount >= 100001.00 && $amount <= 500000.00:
                return $amount * 0.02;
                break;
            case $amount >= 500001.00 && $amount <= 1000000.00:
                return $amount * 0.013;
                break;
            case $amount >= 1000001.00 && $amount <= 5000000.00:
                return $amount * 0.003;
                break;
            case $amount >= 5000001.00 && $amount <= 10000000.00:
                return $amount * 0.0015;
                break;
            case $amount >= 10000001.00:
                if ($currency == 'TZS') {
                    return 20000;
                } else {
                    return round(20000 / $exchangeRate, 2);
                }
                break;
            default:
                abort(404);
        }
    }
}