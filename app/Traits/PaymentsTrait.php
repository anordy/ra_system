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
}