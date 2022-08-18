<?php

namespace App\Http\Controllers\v1;

use App\Enum\PaymentStatus;
use App\Models\Debts\Debt;
use App\Models\Disputes\Dispute;
use App\Models\RenewTaxAgentRequest;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAgent;
use App\Models\ZmBill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendZanMalipoSMS;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\ZmPayment;
use App\Services\ZanMalipo\XmlWrapper;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmSignatureHelper;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class ZanMalipoController extends Controller
{
    private $returnable = [
        StampDutyReturn::class,
        MnoReturn::class,
        VatReturn::class,
        MmTransferReturn::class,
        HotelReturn::class,
        PetroleumReturn::class,
        EmTransactionReturn::class,
        BfoReturn::class,
        LumpSumReturn::class,
        TaxAssessment::class,
        Dispute::class,
        TaxAgent::class,
        RenewTaxAgentRequest::class,
    ];

    private $multipleBillsReturnable = [
        PortReturn::class,
    ];

    private $debtReturnable = [
        Debt::class
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

            $xml           = XmlWrapper::xmlStringToArray($content);
            $arrayToXml    = new ArrayToXml($xml['gepgBillSubResp'], 'gepgBillSubResp');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();

            if (!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgBillSubRespAck', '7303');
            }

            $zan_trx_sts_code =  ZmCore::extractStatusCode($xml['gepgBillSubResp']['BillTrxInf']['TrxStsCode']);
            $bill             = ZmCore::getBill($xml['gepgBillSubResp']['BillTrxInf']['BillId']);

            if ($zan_trx_sts_code == 7101 || $zan_trx_sts_code == 7226) {
                $bill->update(['control_number' => $xml['gepgBillSubResp']['BillTrxInf']['PayCntrNum']]);
                $message = "Your control number for ZRB is {$bill->control_number} for {$bill->description}. Please pay TZS {$bill->amount} before {$bill->expire_date}.";

                if (in_array(array_merge($bill->billable_type, $this->multipleBillsReturnable, $this->debtReturnable), $this->returnable)) {
                    try {
                        $billable         = $bill->billable;
                        $billable->status = ReturnStatus::CN_GENERATED;
                        $billable->save();
                    } catch(\Exception $e){
                        Log::error($e);
                    }
                }

                SendZanMalipoSMS::dispatch(ZmCore::formatPhone($bill->payer_phone_number), $message);
            } else {
                $bill->update(['zan_trx_sts_code' => $zan_trx_sts_code]);

                if (in_array(array_merge($bill->billable_type, $this->multipleBillsReturnable, $this->debtReturnable), $this->returnable)) {
                    try {
                        $billable         = $bill->billable;
                        $billable->status = ReturnStatus::CN_GENERATION_FAILED;
                        $billable->save();
                    } catch (\Exception $e){
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

            $arrayToXml    = new ArrayToXml($xml['gepgPmtSpInfo'], 'gepgPmtSpInfo');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();

            if (!!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgPmtSpInfoAck', '7303');
            }

            $tx_info = $xml['gepgPmtSpInfo']['PymtTrxInf'];

            $bill = ZmCore::getBill($tx_info['BillId']);

            ZmPayment::query()->insert([
                'zm_bill_id'         => $tx_info['BillId'],
                'trx_id'             => $tx_info['TrxId'],
                'sp_code'            => $tx_info['SpCode'],
                'pay_ref_id'         => $tx_info['PayRefId'],
                'control_number'     => $tx_info['PayCtrNum'],
                'bill_amount'        => $tx_info['BillAmt'],
                'paid_amount'        => $tx_info['PaidAmt'],
                'bill_pay_opt'       => $tx_info['BillPayOpt'],
                'currency'           => $tx_info['CCy'],
                'trx_time'           => $tx_info['TrxDtTm'],
                'usd_pay_channel'    => $tx_info['UsdPayChnl'],
                'payer_phone_number' => $tx_info['PyrCellNum'],
                'payer_email'        => $tx_info['PyrEmail'],
                'payer_name'         => $tx_info['PyrName'],
                'psp_receipt_number' => $tx_info['PspReceiptNumber'],
                'psp_name'           => $tx_info['PspName'],
                'ctr_acc_num'        => $tx_info['CtrAccNum'],
            ]);

            if ($bill->paidAmount() >= $bill->amount) {
                $bill->status = 'paid';
            } else {
                $bill->status = 'partially';
            }

            $bill->paid_amount = $bill->paidAmount();
            $bill->save();

            // Check and update return
            $this->updateReturn($bill);

            // Check and update debts
            $this->updateDebt($bill);

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
            $sign   = ZmSignatureHelper::signContent($result);

            return '<Gepg>' . $result . '<gepgSignature>' . $sign . '</gepgSignature></Gepg>';
        } catch (\Throwable $ex) {
            Log::error('GEPG CALLBACK Error: ' . $ex . "\n");

            return $ex->getMessage();
        }
    }

    private function ackResp($msgTag, $codes)
    {
        $signedContent = "<$msgTag><TrxStsCode>$codes</TrxStsCode></$msgTag>";
        $sign          = ZmSignatureHelper::signContent($signedContent);

        return '<Gepg>' . $signedContent . '<gepgSignature>' . $sign . '</gepgSignature></Gepg>';
    }

    private function updateReturn($bill)
    {
        try {
            if (in_array($bill->billable_type, $this->returnable)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $billable         = $bill->billable;
                    $billable->status = ReturnStatus::COMPLETE;
                    $billable->save();
                } else {
                    $billable         = $bill->billable;
                    $billable->status = ReturnStatus::PAID_PARTIALLY;
                    $billable->save();
                }
            } elseif (in_array($bill->billable_type, $this->multipleBillsReturnable)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    // Find the alternative bill for this return
                    $altBill = ZmBill::where('billable_id', $bill->billable_id)
                        ->where('billable_type', $bill->billable_type)
                        ->where('currency', '!=', $bill->currency)
                        ->latest()->first();

                    if (!$altBill) {
                        return;
                    }

                    if ($altBill->status === PaymentStatus::PAID) {
                        $billable         = $bill->billable;
                        $billable->status = ReturnStatus::COMPLETE;
                        $billable->paid_at = Carbon::now()->toDateTimeString();
                        $billable->save();
                    } else {
                        $billable         = $bill->billable;
                        $billable->status = ReturnStatus::COMPLETED_PARTIALLY;
                        $billable->save();
                    }
                } else {
                    $billable         = $bill->billable;
                    $billable->status = ReturnStatus::PAID_PARTIALLY;
                    $billable->save();
                }
            }
        } catch (\Exception $exception){
            Log::error($exception);
        }
    }

    private function updateDebt($bill){
        try {
            if (in_array($bill->billable_type, $this->debtReturnable)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $debt         = $bill->billable;
                    $debt->status = ReturnStatus::COMPLETE;
                    $debt->outstanding_amount = 0;
                    $debt->save();
                } else {
                    $debt         = $bill->billable;
                    $debt->status = ReturnStatus::PAID_PARTIALLY;
                    $debt->outstanding_amount = $bill->amount - $bill->paidAmount();
                    $debt->save();
                }
            }
        } catch(\Exception $e){
            Log::error($e);
        }
    }
}
