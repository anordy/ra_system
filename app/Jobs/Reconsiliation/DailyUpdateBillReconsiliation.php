<?php

namespace App\Jobs\Reconsiliation;

use App\Enum\BillStatus;
use App\Models\ZmBill;
use App\Models\ZmPayment;
use App\Models\ZmRecon;
use App\Models\ZmReconTran;
use App\Traits\AfterPaymentEvents;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyUpdateBillReconsiliation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AfterPaymentEvents;

    public $reconsiliation_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reconsiliation_id)
    {
        //
        $this->reconsiliation_id = $reconsiliation_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->checkReconsiliation($this->reconsiliation_id);
    }

    public function checkReconsiliation($reconsiliation_id){
        Log::info('Job triggered');
        $reconsiliation = ZmRecon::find($reconsiliation_id);

        if ($reconsiliation) {
            $billCtrNums = $reconsiliation->reconTransIDs();

            $bills = ZmBill::whereIn('control_number', $billCtrNums)->whereIn('status', [ BillStatus::PENDING, BillStatus::FAILED ])->get();
            if ($bills) {
                try {
                    DB::beginTransaction();
                    foreach ($bills as $bill) {
                        $reconTrans = ZmReconTran::where('BillCtrNum', $bill->control_number)->firstOrFail();

                        ZmPayment::query()->insert([
                            'zm_bill_id' => $bill->id,
                            'trx_id' => $reconTrans['pspTrxId'],
                            'sp_code' => config('modulesconfig.sp_code'),
                            'pay_ref_id' => $reconTrans['PayRefId'],
                            'control_number' => $reconTrans['BillCtrNum'],
                            'bill_amount' => $bill['amount'],
                            'paid_amount' => $reconTrans['PaidAmt'],
                            'bill_pay_opt' => 1,
                            'currency' => $reconTrans['CCy'],
                            'trx_time' => $reconTrans['TrxDtTm'],
                            'usd_pay_channel' => $reconTrans['UsdPayChnl'],
                            'payer_phone_number' => $reconTrans['DptCellNum'],
                            'payer_email' => $reconTrans['DptEmailAddr'],
                            'payer_name' => $reconTrans['DptName'],
                            'psp_receipt_number' =>'RST' . random_int(10000, 90000),
                            'psp_name' => $reconTrans['PspName'],
                            'ctr_acc_num' => $reconTrans['CtrAccNum'],
                            'created_by_recon' => true,
                            'recon_trans_id' => $reconTrans['id'],
                            'created_at' => Carbon::now()->toDateTimeString(),
                        ]);

                        $bill->status = 'paid';
                        $bill->paid_amount = $bill->amount;
                        $bill->save();

                        $this->updateBillable($bill);

                        // Check and update tax return & Return
                        $this->updateTaxReturn($bill);

                        // Check and Update installments
                        $this->updateInstallment($bill);

                        // Check and Update Disputes
                        $this->updateAssessment($bill);

                        //Check and Update Lease Payment
                        $this->updateLeasePayment($bill);
                    }
                    DB::commit();

                        Log::info('Successfully => Reconsiliation: Update Bills which didnt receive payment callback');
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::info('Failed => Reconsiliation: Update paid bills which didnt receive payment callback');
                        Log::error($e);
                    }

            }

        }

    }
}
