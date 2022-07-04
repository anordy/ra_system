<?php

namespace App\Services\ZanMalipo;


use App\Models\ZmBill;
use App\Models\ZmBillItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class ZmCore
{

    const PAYMENT_OPTION_FULL = 1;
    const PAYMENT_OPTION_PARTIAL = 2;
    const PAYMENT_OPTION_EXACT = 3;


    public static function getBill($bill_id)
    {
        return ZmBill::query()->find($bill_id);
    }
    /**
     * @throws \DOMException
     * @throws \Exception
     */
    public static function createAndSendBill(
        $payer_id,
        $payer_name,
        $payer_email,
        $phone_number,
        $expire_date,
        $bill_desc,
        $bill_items,
        $payment_option,
        $currency = 'TZS',
        $generated_by = 'ZPC',
        $approved_by = 'ZPC'
    ): ZmResponse {
        $zm_bill = self::createBill($payer_id, $payer_name, $payer_email, $phone_number, $expire_date, $bill_desc, $bill_items, $payment_option, $currency);

        return  self::sendBill($zm_bill, $generated_by, $approved_by);
    }

    /**
     *
     * Create bill (without posting to ZM)
     * @param $expire_date
     * @param $payer_id
     * @param $payer_name
     * @param $phone_number
     * @param $payer_email
     * @param $bill_desc
     * @param $bill_items
     * @return array
     */
    public static function createBill(
        $payer_id,
        $payer_name,
        $payer_email,
        $phone_number,
        $expire_date,
        $bill_desc,
        $bill_items,
        $payment_option,
        $currency = 'TZS'
    ): ZmBill {
        $bill_amount = 0;
        foreach ($bill_items as $item) {
            if (!isset($item['item_amount']) || !isset($item['gfs_code'])) {
                throw new \Exception('Bill item must contain item_amount and gfs_code');
            }
            $bill_amount += $item['item_amount'];
        }

        $zm_bill = new ZmBill([
            'bill_amount' => $bill_amount,
            'misc_amount' => 0,
            'equivalent_amount' => $bill_amount,
            'expire_date' => $expire_date,
            'payer_id' => $payer_id,
            'payer_name' => $payer_name,
            'payer_phone_number' => $phone_number,
            'payer_email' => $payer_email,
            'currency' => $currency,
            'description' => $bill_desc,
            'payment_option' => $payment_option,
            'status' => 'PENDING',
            'bill_gen_date' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $zm_bill->save();

        foreach ($bill_items as $item) {
            $zm_item = new ZmBillItem([
                'bill_id' => $zm_bill->id,
                'use_item_ref_on_pay' => 'N',
                'item_amount' => $item['item_amount'],
                'item_eqv_amount' => $item['item_amount'],
                'item_misc_amount' => '0',
                'gfs_code' => $item['gfs_code']
            ]);
            $zm_item->save();
        }
        return $zm_bill;
    }


    /**
     * @param Bill|int $bill Instance of ZmBill or bill id
     * @param string $generated_by
     * @param string $approved_by
     * @return ZmResponse
     * @throws \DOMException
     */
    public static function sendBill($bill, $generated_by = 'ZPC', $approved_by = 'ZPC'): ZmResponse
    {
        if (is_numeric($bill)) {
            $zm_bill = ZmBill::query()->find($bill);
        } else if ($bill instanceof ZmBill) {
            $zm_bill = $bill;
        } else {
            throw new \Exception('Invalid bill supplied to send bill');
        }
        $xml = new XmlWrapper('gepgBillSubReq');
        $xml_bill_hdr = $xml->createElement("BillHdr");
        $xml->addChildrenToNode([
            'SpCode' => config('modulesconfig.zm_spcode'),
            'RtrRespFlg' => 'true'
        ], $xml_bill_hdr);

        $xml_trx_info = $xml->createElement("BillTrxInf");

        $payer = DB::select('CALL sp_get_customer_service_application(?)', [$zm_bill->customer_service_applications_id])[0];
        $xml->addChildrenToNode([
            'BillId' => $zm_bill->id,
            'SubSpCode' => config('modulesconfig.zm_subspcode'),
            'SpSysId' =>  config('modulesconfig.zm_spsysid'),
            'BillAmt' => $zm_bill->amount_tzs,
            'MiscAmt' => 0,
            'BillExprDt' => Carbon::createFromFormat('Y-m-d H:i:s', $zm_bill->expiring_datetime)->format('Y-m-d\TH:i:s'),
            'PyrId' => $payer->id,
            'PyrName' => $payer->payer_full_name,
            'BillDesc' => $zm_bill->description,
            'BillGenDt' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'BillGenBy' => $generated_by,
            'BillApprBy' => $approved_by,
            'PyrCellNum' => self::formatPhone($payer->payer_phone_number),
            'PyrEmail' => $payer->payer_email_address,
            'Ccy' => 'TZS',
            'BillEqvAmt' => $zm_bill->amount_tzs,
            'RemFlag' => 'true',
            'BillPayOpt' => $zm_bill->payment_option,
        ], $xml_trx_info);

        $xml_bill_items = $xml->createElement("BillItems");

        if (count($zm_bill->bill_items) == 0) {
            throw new \Exception('Bill has no bill items (in customer_application_bill_items)');
        }

        foreach ($zm_bill->bill_items as $zm_item) {
            $bill_item = [
                'BillItemRef' => $zm_item->id,
                'UseItemRefOnPay' => 'N',
                'BillItemAmt' => $zm_item->item_amount,
                'BillItemEqvAmt' => $zm_item->item_amount,
                'BillItemMiscAmt' => 0,
                'GfsCode' => $zm_item->service_code
            ];

            $xml_bill = $xml->createElement('BillItem');
            $xml->addChildrenToNode($bill_item, $xml_bill);
            $xml->addChildNodeToNode($xml_bill, $xml_bill_items);
        }

        $xml->addChildNodeToNode($xml_bill_items, $xml_trx_info);
        $xml->addToRoot($xml_bill_hdr);
        $xml->addToRoot($xml_trx_info);

        $response = self::signrequest($xml->toXML(), config('modulesconfig.zm_create_bill'));
        $status_code = self::getStatusCode($response);

        $zm_bill->trx_sts_code = $status_code;

        if ($status_code == 7101) {
            $zm_bill->zm_posting_status = 'PENDING';
        } else {
            $zm_bill->zm_posting_status = 'FAILED';
        }
        $zm_bill->save();

        return new ZmResponse($status_code, $zm_bill);
    }

    /**
     * @throws \DOMException
     */
    public static function updateBill($bill_id, $expire_date)
    {
        $bill = ZmBill::query()->find($bill_id);

        if (empty($bill)) {
            throw new \Exception('Bill does not exist');
        }

        $gsb = [
            'BillHdr' => [
                'SpCode' => config('modulesconfig.zm_spcode'),
                'RtrRespFlg' => 'true'
            ],
            'BillTrxInf' => [
                'BillId' => $bill_id,
                'SpSysId' =>  config('modulesconfig.zm_spsysid'),
                'BillExprDt' => $expire_date,
            ]
        ];

        $arrayToXml = new ArrayToXml($gsb, 'gepgBillSubReq');
        $url = config('modulesconfig.zm_update_bill');
        $response = self::signrequest($arrayToXml->dropXmlDeclaration()->toXml(), $url, 'changebill.sp.in');
        $status_code = self::getStatusCode($response);

        return new ZmResponse($status_code, null);
    }


    /**
     * @param $date
     * @param $opt
     * @return ZmResponse
     * @throws \DOMException
     */
    public static function inquireRecon($date, $opt)
    {
        $gsb = [
            'SpReconcReqId' => random_int(100000, 999999),
            'SpCode' => config('modulesconfig.zm_spcode'),
            'SpSysId' =>  config('modulesconfig.zm_spsysid'),
            'TnxDt' => $date,
            'ReconcOpt' => $opt,
        ];

        $arrayToXml = new ArrayToXml($gsb, 'gepgSpReconcReq');

        $response =  self::signrequest($arrayToXml->dropXmlDeclaration()->toXml(), config('modulesconfig.zm_recon'));
        $status_code = self::getStatusCode($response, 'gepgSpReconcReqAck', 'ReconcStsCode');

        return new ZmResponse($status_code, null);
    }


    public static function cancelBill($bill_id, $reason)
    {

        $gsb = [
            'SpCode' => config('modulesconfig.zm_spcode'),
            'SpSysId' =>  config('modulesconfig.zm_spsysid'),
            'CanclReasn' => $reason,
            'BillId' => $bill_id,
        ];

        $arrayToXml = new ArrayToXml($gsb, 'gepgBillCanclReq');
        $url = config('modulesconfig.zm_cancel');

        $response = self::signrequest($arrayToXml->dropXmlDeclaration()->toXml(), $url);

        $status_code = self::getStatusCode($response, 'gepgBillCanclResp', 'BillCanclTrxDt', 'TrxStsCode');
        if ($status_code == ZmResponse::ZM_BILL_CANCELLED) {
            $bill = ZmBill::query()->find($bill_id);
            if (!$bill->update(['bill_status_code' => 'CN001', 'cancellation_reason' => $reason])) {
                $status_code = ZmResponse::FAILED_DB_UPDATE_ERROR;
            } else {
                $status_code = ZmResponse::SUCCESS;
            }
        }

        return new ZmResponse($status_code, null, $response);
    }


    private static function getStatusCode($response, $dt_tag1 = 'gepgBillSubReqAck', $dt_tag2 = 'TrxStsCode', $dt_tag3 = null)
    {
        if (empty($response) || ($response = XmlWrapper::xmlStringToArray($response)) == null) {
            return ZmResponse::FAILED_COMMUNICATION_ERROR;
        }
        if (empty($dt_tag3)) {
            $trx_status = $response[$dt_tag1][$dt_tag2];
        } else {
            $trx_status = $response[$dt_tag1][$dt_tag2][$dt_tag3];
        }

        $trx_status = self::extractStatusCode($trx_status);
        if ($trx_status == 7101) {
            return ZmResponse::SUCCESS;
        }

        return $trx_status;
    }

    /**
     * @param $trx_status
     * @return mixed|string
     */
    public static function extractStatusCode($trx_status)
    {
        if (preg_match('/;/', $trx_status)) {
            $trx_status = preg_replace('/7201/', '', $trx_status);
            $trx_status = trim(trim($trx_status), ';');
            $trx_status = explode(';', $trx_status)[0];
        }
        return $trx_status;
    }

    /**
     * @throws \Exception
     */
    public static function signrequest($result, $apiURL, $com_header = 'default.sp.in')
    {
        $content = $result;
        $sign = ZmSignatureHelper::signContent($content);
        if (!empty($sign)) {
            //Compose xml request
            $data = "<Gepg>" . $content . "<gepgSignature>" . $sign . "</gepgSignature></Gepg>";
            Log::info("ZAN MALIPO REQUEST: " . $data);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiURL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => '',
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/xml",
                    "Accept: application/xml",
                    "Gepg-Com:" . $com_header,
                    //"Gepg-Com:reusebill.sp.in",
                    "Gepg-Code:" . config('modulesconfig.zm_spcode')
                ),
            ));

            try {
                $response = curl_exec($curl);
                if ($curl) $err = curl_error($curl);
                curl_close($curl);
                Log::info("error: " . $err);
                Log::info("Resp: " . $response);
                return $response;
            } catch (\Throwable $ex) {
                Log::info("Curl Error: " . $ex->getMessage() . "\n");
                Log::info("Curl Error: " . $ex->getTraceAsString() . "\n");
                if ($curl) {
                    $err = curl_error($curl);
                    curl_close($curl);
                    Log::info("Curl Error: " . $err . "\n");
                }
                return null;
            }
        } else {

            Log::info("Error: Unable to read the cert store.\n");
            return null;
        }
    }

    public static function formatPhone($phone_number)
    {
        $phone_number = preg_replace('/^0/', '255', $phone_number);
        if (strlen($phone_number) == 9) {
            return '255' . $phone_number;
        } else if (preg_match('/^255[0-9]{9}/', $phone_number)) {
            return $phone_number;
        }
        return '';
    }
}
