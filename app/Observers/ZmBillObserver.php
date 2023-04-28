<?php

namespace App\Observers;

use App\Jobs\RepostBillSignature;
use App\Models\ZmBill;
use App\Traits\VerificationTrait;
use Illuminate\Support\Facades\Log;
use PHPUnit\Exception;

class ZmBillObserver
{
    use VerificationTrait;
    /**
     * Handle the ZmBill "created" event.
     *
     * @param  \App\Models\ZmBill  $zmBill
     * @return void
     */
    public function created(ZmBill $zmBill)
    {
        try {
            if (!$this->sign($zmBill)){
                dispatch(new RepostBillSignature($zmBill));
            }
        } catch (Exception $exception){
            Log::channel('verification')->error('Something went wrong, please contact support for assistance.');
            dispatch(new RepostBillSignature($zmBill));
        }
    }

    /**
     * Handle the ZmBill "updated" event.
     *
     * @param  \App\Models\ZmBill  $zmBill
     * @return void
     */
    public function updated(ZmBill $zmBill)
    {
        try {
            if (!$this->sign($zmBill)){
                dispatch(new RepostBillSignature($zmBill));
            }
        } catch (Exception $exception){
            Log::channel('verification')->error('Something went wrong, please contact support for assistance.');
            dispatch(new RepostBillSignature($zmBill));
        }
    }

    /**
     * Handle the ZmBill "deleted" event.
     *
     * @param  \App\Models\ZmBill  $zmBill
     * @return void
     */
    public function deleted(ZmBill $zmBill)
    {
        //
    }

    /**
     * Handle the ZmBill "restored" event.
     *
     * @param  \App\Models\ZmBill  $zmBill
     * @return void
     */
    public function restored(ZmBill $zmBill)
    {
        //
    }

    /**
     * Handle the ZmBill "force deleted" event.
     *
     * @param  \App\Models\ZmBill  $zmBill
     * @return void
     */
    public function forceDeleted(ZmBill $zmBill)
    {
        //
    }
}
