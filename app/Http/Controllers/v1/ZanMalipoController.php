<?php


namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Jobs\SendSMS;
use App\Models\ZmBillPayment;
use App\Models\ZmPayment;
use App\Services\ZanMalipo\XmlWrapper;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmSignatureHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;


class ZanMalipoController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    function controlNumberCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info("ZAN MALIPO CALLBACK: " . $content . "\n");

            $xml = XmlWrapper::xmlStringToArray($content);
            $arrayToXml = new ArrayToXml($xml['gepgBillSubResp'], 'gepgBillSubResp');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();
            if (!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgBillSubRespAck', '7303');
            }

            $trx_status_code =  ZmCore::extractStatusCode($xml['gepgBillSubResp']['BillTrxInf']['TrxStsCode']);
            $bill = ZmCore::getBill($xml['gepgBillSubResp']['BillTrxInf']['BillId']);
            if ($trx_status_code == 7101 || $trx_status_code == 7226) {
                $bill->update(['control_number' => $xml['gepgBillSubResp']['BillTrxInf']['PayCntrNum']]);
                $payers = DB::select('CALL sp_get_customer_service_application(?)', [$bill->customer_service_applications_id]);

                if (count($payers) > 0) {
                    $message = "Your control number for ZPC is {$bill->control_number}. Please pay TZS {$bill->amount_tzs} before {$bill->expiring_datetime}.";
                    SendSMS::dispatch(ZmCore::formatPhone($payers[0]->payer_phone_number), $message);
                }
            } else {
                $bill->update(['trx_sts_code' => $trx_status_code]);
            }


            return $this->ackResp('gepgBillSubRespAck', '7101');
        } catch (\Throwable $ex) {
            Log::error("GEPG CALLBACK Error: " . $ex . "\n");
            return $ex->getMessage();
        }
    }

    private function sendToOther($content, $url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => '',
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/xml",
                "Accept: application/xml"
            ),
        ));
        try {
            $response = curl_exec($curl);
            if ($curl) $err = curl_error($curl);
            curl_close($curl);
            Log::info("ARDHI ERR: ========== " . $err);
            Log::info("ARDHI RES: ========== " . $response);
        } catch (\Throwable $ex) {
            Log::info("ARDHI EXC: ========== " . $ex->getMessage() . "\n");
            if ($curl) {
                $err = curl_error($curl);
                curl_close($curl);
                Log::info("Curl ARDHI EXC: ========== " . $err . "\n");
            }
        }
    }

    function paymentCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info("ZAN MALIPO PAYMENT CALLBACK: " . $content . "\n");

            $xml = XmlWrapper::xmlStringToArray($content);

            $arrayToXml = new ArrayToXml($xml['gepgPmtSpInfo'], 'gepgPmtSpInfo');
            $signedContent = $arrayToXml->dropXmlDeclaration()->toXml();

            if (!!ZmSignatureHelper::verifySignature($xml['gepgSignature'], $signedContent)) {
                return $this->ackResp('gepgPmtSpInfoAck', '7303');
            }
            $tx_info = $xml['gepgPmtSpInfo']['PymtTrxInf'];

            $bill = ZmCore::getBill($tx_info['BillId']);
            ZmPayment::query()->insert([
                'bill_id' => $tx_info['BillId'],
                'trx_id' => $tx_info['TrxId'],
                'sp_code' => $tx_info['SpCode'],
                'pay_ref_id' => $tx_info['PayRefId'],
                'control_number' => $tx_info['PayCtrNum'],
                'bill_amount' => $tx_info['BillAmt'],
                'paid_amount' => $tx_info['PaidAmt'],
                'bill_pay_option' => $tx_info['BillPayOpt'],
                'currency' => $tx_info['CCy'],
                'trx_time' => $tx_info['TrxDtTm'],
                'usd_pay_channel' => $tx_info['UsdPayChnl'],
                'payer_phone_number' => $tx_info['PyrCellNum'],
                'payer_email' => $tx_info['PyrEmail'],
                'payer_name' => $tx_info['PyrName'],
                'psp_receipt_number' => $tx_info['PspReceiptNumber'],
                'psp_name' => $tx_info['PspName'],
                'ctr_acc_num' => $tx_info['CtrAccNum']
            ]);

            //            insert into bills daily table
            DB::select('call sp_insert_daily_bill(?,?,?,?,?)', array(
                $bill->service_group_code,
                $tx_info['PaidAmt'],
                $tx_info['PaidAmt'] / $bill->exchange_rate,
                $bill->exchange_rate,
                null
            ));

            if ($bill->paid_amount() >= $bill->bill_amount) {
                $bill->bill_status_code = 'PA001';
                $bill->zm_posting_status = 'PAID';
            } else {
                $bill->bill_status_code = 'PT001';
                $bill->zm_posting_status = 'PARTIAL';
            }

            $bill->save();

            //TODO: we should send sms to customer here to notify payment reception

            return $this->ackResp('gepgPmtSpInfoAck', '7101');
        } catch (\Throwable $ex) {
            Log::error("GEPG CALLBACK Error: " . $ex . "\n");
            return $ex->getMessage();
        }
    }

    function reconCallback(Request $request)
    {
        try {
            $content = $request->getContent();
            Log::info("GEPG RECON CALLBACK CONTENT: " . $content . "\n");

            $result = "<gepgSpReconcRespAck><ReconcStsCode>7101</ReconcStsCode></gepgSpReconcRespAck>";
            $sign = ZmSignatureHelper::signContent($result);
            return "<Gepg>" . $result . "<gepgSignature>" . $sign . "</gepgSignature></Gepg>";
        } catch (\Throwable $ex) {
            Log::error("GEPG CALLBACK Error: " . $ex . "\n");
            return $ex->getMessage();
        }
    }

    private function ackResp($msgTag, $codes)
    {
        $signedContent = "<$msgTag><TrxStsCode>$codes</TrxStsCode></$msgTag>";
        $sign = ZmSignatureHelper::signContent($signedContent);
        return "<Gepg>" . $signedContent . "<gepgSignature>" . $sign . "</gepgSignature></Gepg>";
    }
}
