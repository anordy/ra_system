<?php

namespace App\Http\Controllers\v1;

use App\Enum\BillStatus;
use App\Enum\DisputeStatus;
use App\Enum\InstallmentStatus;
use App\Enum\LeaseStatus;
use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Jobs\SendZanMalipoSMS;
use App\Models\Disputes\Dispute;
use App\Models\Installment\InstallmentItem;
use App\Models\LandLease;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\ZmBill;
use App\Models\ZmPayment;
use App\Services\ZanMalipo\XmlWrapper;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmSignatureHelper;
use App\Traits\LandLeaseTrait;
use App\Traits\TaxVerificationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class ZanMalipoController extends Controller
{
    use TaxVerificationTrait;

    private $billable = [
        PortReturn::class,
        LeasePayment::class,
    ];

    private $installable = [
        InstallmentItem::class,
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function controlNumberCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info('ZAN MALIPO CALLBACK: ' . $content . "\n");

            $xml = XmlWrapper::xmlStringToArray($content);
            $arrayToXml = new ArrayToXml($xml['gepgBillSubResp'], 'gepgBillSubResp');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();

            if (!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgBillSubRespAck', '7303');
            }

            $zan_trx_sts_code = ZmCore::extractStatusCode($xml['gepgBillSubResp']['BillTrxInf']['TrxStsCode']);
            $bill = ZmCore::getBill($xml['gepgBillSubResp']['BillTrxInf']['BillId']);

            if ($zan_trx_sts_code == 7101 || $zan_trx_sts_code == 7226) {
                $bill->update(['control_number' => $xml['gepgBillSubResp']['BillTrxInf']['PayCntrNum']]);
                $message = "Your control number for ZRB is {$bill->control_number} for {$bill->description}. Please pay TZS {$bill->amount} before {$bill->expire_date}.";

                if (in_array($bill->billable_type, array_merge(
                    $this->billable,
                    [TaxReturn::class, TaxAssessment::class],
                    $this->installable))) {
                    try {
                        $billable = $bill->billable;
                        $billable->status = ReturnStatus::CN_GENERATED;
                        $billable->save();
                    } catch (\Exception $e) {
                        Log::error($e);
                    }
                }

                SendZanMalipoSMS::dispatch(ZmCore::formatPhone($bill->payer_phone_number), $message);
            } else {
                $bill->update(['zan_trx_sts_code' => $zan_trx_sts_code]);

                if (in_array($bill->billable_type, array_merge(
                    $this->billable,
                    [TaxReturn::class, TaxAssessment::class],
                    $this->installable))) {
                    try {
                        $billable = $bill->billable;
                        $billable->status = ReturnStatus::CN_GENERATION_FAILED;
                        $billable->save();
                    } catch (\Exception $e) {
                        Log::error($e);
                    }
                }
            }

            return $this->ackResp('gepgBillSubRespAck', '7101');
        } catch (\Throwable $ex) {
            Log::error('GEPG CALLBACK Error: ' . $ex . "\n");

            return $ex->getMessage();
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info('ZAN MALIPO PAYMENT CALLBACK: ' . $content . "\n");

            $xml = XmlWrapper::xmlStringToArray($content);

            $arrayToXml = new ArrayToXml($xml['gepgPmtSpInfo'], 'gepgPmtSpInfo');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();

            if (!!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgPmtSpInfoAck', '7303');
            }

            $tx_info = $xml['gepgPmtSpInfo']['PymtTrxInf'];

            $bill = ZmCore::getBill($tx_info['BillId']);

            ZmPayment::query()->insert([
                'zm_bill_id' => $tx_info['BillId'],
                'trx_id' => $tx_info['TrxId'],
                'sp_code' => $tx_info['SpCode'],
                'pay_ref_id' => $tx_info['PayRefId'],
                'control_number' => $tx_info['PayCtrNum'],
                'bill_amount' => $tx_info['BillAmt'],
                'paid_amount' => $tx_info['PaidAmt'],
                'bill_pay_opt' => $tx_info['BillPayOpt'],
                'currency' => $tx_info['CCy'],
                'trx_time' => $tx_info['TrxDtTm'],
                'usd_pay_channel' => $tx_info['UsdPayChnl'],
                'payer_phone_number' => $tx_info['PyrCellNum'],
                'payer_email' => $tx_info['PyrEmail'],
                'payer_name' => $tx_info['PyrName'],
                'psp_receipt_number' => $tx_info['PspReceiptNumber'],
                'psp_name' => $tx_info['PspName'],
                'ctr_acc_num' => $tx_info['CtrAccNum'],
            ]);

            if ($bill->paidAmount() >= $bill->amount) {
                $bill->status = 'paid';
            } else {
                $bill->status = 'partially';
            }

            $bill->paid_amount = $bill->paidAmount();
            $bill->save();

            // Check and update billable status
            $this->updateBillable($bill);

            // Check and update tax return & Return
            $this->updateTaxReturn($bill);

            // Update installments
            $this->updateInstallment($bill);

            // Update Disputes
            $this->updateAssessment($bill);

            //Update Lease Payment
            $this->updateLeasePayment($bill);

            //TODO: we should send sms to customer here to notify payment reception

            return $this->ackResp('gepgPmtSpInfoAck', '7101');
        } catch (\Throwable $ex) {
            Log::error('GEPG CALLBACK Error: ' . $ex . "\n");

            return $ex->getMessage();
        }
    }

    public function reconCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info('GEPG RECON CALLBACK CONTENT: ' . $content . "\n");

            $result = '<gepgSpReconcRespAck><ReconcStsCode>7101</ReconcStsCode></gepgSpReconcRespAck>';
            $sign = ZmSignatureHelper::signContent($result);

            return '<Gepg>' . $result . '<gepgSignature>' . $sign . '</gepgSignature></Gepg>';
        } catch (\Throwable $ex) {
            Log::error('GEPG CALLBACK Error: ' . $ex . "\n");

            return $ex->getMessage();
        }
    }

    private function ackResp($msgTag, $codes)
    {
        $signedContent = "<$msgTag><TrxStsCode>$codes</TrxStsCode></$msgTag>";
        $sign = ZmSignatureHelper::signContent($signedContent);

        return '<Gepg>' . $signedContent . '<gepgSignature>' . $sign . '</gepgSignature></Gepg>';
    }

    private function updateBillable($bill)
    {
        try {
            if (in_array($bill->billable_type, $this->billable)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $billable = $bill->billable;
                    $billable->status = ReturnStatus::COMPLETE;
                    $billable->paid_at = Carbon::now()->toDateTimeString();
                    $billable->save();
                } else {
                    $billable = $bill->billable;
                    $billable->status = ReturnStatus::PAID_PARTIALLY;
                    $billable->save();
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }

    private function updateTaxReturn($bill)
    {
        try {
            if ($bill->billable_type == TaxReturn::class) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $tax_return = $bill->billable;
                    $return = $tax_return->return;
                    if ($return) {
                        $return->status = ReturnStatus::COMPLETE;
                        $return->paid_at = Carbon::now()->toDateTimeString();
                        $return->save();

                        // Trigger verifications approval
                        $this->initiateVerificationApproval($return);
                    }
                    $tax_return->payment_status = ReturnStatus::COMPLETE;
                    $tax_return->outstanding_amount = 0;
                    $tax_return->save();
                } else {
                    $tax_return = $bill->billable;
                    $tax_return->status = ReturnStatus::PAID_PARTIALLY;
                    $tax_return->outstanding_amount = $bill->amount - $bill->paidAmount();
                    $tax_return->save();
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function updateAssessment($bill)
    {
        try {
            $assessmentBillItems = $bill->bill_items->pluck('billable_type')->toArray();
            if ($bill->billable_type == TaxAssessment::class && in_array(Dispute::class, $assessmentBillItems)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $dispute = $bill->bill_items()->where('billable_type', Dispute::class)->first()->billable;

                    $assessment = $bill->billable;
                    $assessment->payment_status = BillStatus::PAID_PARTIALLY;
                    $assessment->save();

                    $this->registerWorkflow(get_class($dispute), $dispute->id);
                    $this->doTransition('application_submitted', []);
                    $dispute->app_status = DisputeStatus::SUBMITTED;
                    $dispute->save();
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    private function updateInstallment($bill)
    {
        try {
            if ($bill->billable_type == InstallmentItem::class) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $item = $bill->billable;
                    $item->update([
                        'status' => ReturnStatus::COMPLETE,
                        'paid_at' => Carbon::now()->toDateTimeString(),
                    ]);

                    $taxReturn = $item->installment->taxReturn;
                    $taxReturn->update([
                        'outstanding_amount' => $taxReturn->outstanding_amount - $bill->amount,
                    ]);

                    if ($item->installment->getNextPaymentDate()) {
                        $taxReturn->update([
                            'curr_payment_due_date' => $item->installment->getNextPaymentDate(),
                        ]);
                    } elseif (!$item->installment->getNextPaymentDate() && ($item->installment->status == InstallmentStatus::ACTIVE)) {
                        $item->installment->update([
                            'status' => InstallmentStatus::COMPLETE,
                        ]);

                        $item->installment->taxReturn->update([
                            'status' => ReturnStatus::COMPLETE,
                        ]);

                        $item->installment->taxReturn->return->update([
                            'status' => ReturnStatus::COMPLETE,
                        ]);
                    }
                } else {
                    $item = $bill->billable;
                    $item->update([
                        'status' => ReturnStatus::PAID_PARTIALLY,
                    ]);

                    $taxReturn = $item->installment->taxReturn;
                    $taxReturn->update([
                        'outstanding_amount' => $taxReturn->outstanding_amount - $bill->amount,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    // TODO: Remove on production
    public function pay(Request $request)
    {
        if (config('app.env') != 'local') {
            Log::alert('Bypassing payments on production.');
        }

        if ($request->control_number) {
            $bill = ZmBill::where('control_number', $request->control_number)->firstOrFail();
        } else {
            $request->validate([
                'bill_id' => 'required',
            ]);

            $bill = ZmBill::findOrFail($request->bill_id);
        }

        try {
            DB::beginTransaction();
            $payment = ZmPayment::query()->insert([
                'zm_bill_id' => $bill->id,
                'trx_id' => rand(100000, 1000000),
                'sp_code' => 'SP20007',
                'pay_ref_id' => rand(100000, 1000000),
                'control_number' => $bill->control_number,
                'bill_amount' => $bill->amount,
                'paid_amount' => $bill->amount,
                'bill_pay_opt' => $bill->payment_option,
                'currency' => $bill->currency,
                'trx_time' => Carbon::now()->toDateTimeString(),
                'usd_pay_channel' => 'BANK',
                'payer_phone_number' => '0753' . rand(100000, 900000),
                'payer_email' => 'meshackf1@gmail.com',
                'payer_name' => 'John Doe',
                'psp_receipt_number' => 'RST' . rand(10000, 90000),
                'psp_name' => 'BANK 1',
                'ctr_acc_num' => rand(100000000, 900000000),
                'created_at' => Carbon::now()->toDateTimeString(),
            ]);

            if ($bill->paidAmount() >= $bill->amount) {
                $bill->status = 'paid';
            } else {
                $bill->status = 'partially';
            }

            $bill->paid_amount = $bill->paidAmount();
            $bill->save();

            // Check and update return
            $this->updateBillable($bill);

            // Check and update tax return
            $this->updateTaxReturn($bill);

            // Update installments
            $this->updateInstallment($bill);

            // Update disputes
            $this->updateAssessment($bill);

            //Land Lease
            $this->updateLeasePayment($bill);

            DB::commit();
            return $payment;

        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    use LandLeaseTrait;
    private function updateLeasePayment($bill){
        
        try {
            if ($bill->billable_type == LeasePayment::class) {
                $updateLeasePayment = $bill->billable;

                if ($bill->paidAmount() >= $bill->amount) {

                    if(Carbon::now()->month < Carbon::parse($updateLeasePayment->due_date)->month){
                        $status = LeaseStatus::IN_ADVANCE_PAYMENT;
                    } else if(Carbon::now()->month == Carbon::parse($updateLeasePayment->due_date)->month){
                        $status = LeaseStatus::ON_TIME_PAYMENT;
                    } else if(Carbon::now()->month > Carbon::parse($updateLeasePayment->due_date)->month){
                        $status = LeaseStatus::LATE_PAYMENT;
                    }
                    $updateLeasePayment->status = $status;                
                } else {

                    $updateLeasePayment->status = LeaseStatus::PAID_PARTIALLY;
                }

                $updateLeasePayment->outstanding_amount = $bill->amount - $bill->paidAmount();
                $updateLeasePayment->paid_at = Carbon::now();
                $updateLeasePayment->save();


                if ($updateLeasePayment->debt) {
                    $updateDebt = LandLeaseDebt::find($updateLeasePayment->debt->id);
                    $updateDebt->status = LeaseStatus::COMPLETE;
                    $updateDebt->outstanding_amount = $updateLeasePayment->outstanding_amount;
                    $updateDebt->save();
                }
                
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
