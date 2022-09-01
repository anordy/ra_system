<?php

namespace App\Services\ZanMalipo;

use App\Models\Disputes\Dispute;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\ZmBill;
use App\Models\ZmBillItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class ZmCore
{

    const PAYMENT_OPTION_FULL = 1;
    const PAYMENT_OPTION_PARTIAL = 2;
    const PAYMENT_OPTION_EXACT = 3;


    public static function getBill($billId)
    {
        return ZmBill::query()->find($billId);
    }

    /**
     *
     * Create bill (without posting to ZM)
     * @param $billable_id
     * @param $billable_type
     * @param $taxTypeId
     * @param $payer_id
     * @param $payer_type
     * @param $payer_name
     * @param $payer_email
     * @param $payer_phone_number
     * @param $expire_date
     * @param $payment_description
     * @param $payment_option
     * @param $currency
     * @param $exchange_rate
     * @param $createdby_id
     * @param $createdby_type
     * @param $bill_items
     * @return ZmBill
     * @throws Exception
     */

    public static function createBill(
        $billable_id,
        $billable_type,
        $taxTypeId,
        $payer_id,
        $payer_type,
        $payer_name,
        $payer_email,
        $payer_phone_number,
        $expire_date,
        $payment_description,
        $payment_option,
        $currency = 'TZS',
        $exchange_rate,
        $createdby_id = null,
        $createdby_type = null,
        $bill_items
    ): ZmBill {
        DB::beginTransaction();
        try {
            $bill_items = ZmFeeHelper::addTransactionFee($bill_items, $currency, $exchange_rate);
            $bill_amount = 0;
            foreach ($bill_items as $item) {
                if (!isset($item['amount']) || !isset($item['gfs_code'])) {
                    throw new \Exception('Bill item must contain amount and gfs_code');
                }
                if ($item['currency'] != 'TZS') {
                    $bill_amount = $exchange_rate * $item['amount'];
                } else {
                    $bill_amount += $item['amount'];
                }
            }

            $equivalent_amount = $bill_amount * $exchange_rate;

            $zm_bill = new ZmBill([
                'billable_id' => $billable_id,
                'billable_type' => $billable_type,
                'tax_type_id' => $taxTypeId,
                'amount' => $bill_amount,
                'exchange_rate' => $exchange_rate,
                'equivalent_amount' => $equivalent_amount,
                'expire_date' => $expire_date,
                'payer_id' => $payer_id,
                'payer_type' => $payer_type,
                'payer_name' => $payer_name,
                'payer_phone_number' => $payer_phone_number,
                'payer_email' => $payer_email,
                'currency' => $currency,
                'description' => $payment_description,
                'payment_option' => $payment_option,
                'status' => 'pending',
                'createdby_id' => $createdby_id,
                'createdby_type' => $createdby_type,
            ]);

            $zm_bill->save();

            foreach ($bill_items as $item) {
                $zm_item = new ZmBillItem([
                    'zm_bill_id' => $zm_bill->id,
                    'billable_id' => array_key_exists('billable_id', $item)  ? $item['billable_id'] : null,
                    'billable_type' => array_key_exists('billable_type', $item)  ? $item['billable_type'] : null,
                    'fee_id' => array_key_exists('fee_id', $item)  ? $item['fee_id'] : null,
                    'fee_type' =>  array_key_exists('fee_type', $item)? $item['fee_type'] : null,
                    'use_item_ref_on_pay' => 'N',
                    'tax_type_id' => $item['tax_type_id'],
                    'amount' => $item['amount'],
                    'currency' => $item['currency'],
                    'exchange_rate' => $exchange_rate,
                    'equivalent_amount' => $exchange_rate * $item['amount'],
                    'gfs_code' => $item['gfs_code']
                ]);
                $zm_item->save();
            }
            DB::commit();
            return $zm_bill;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new Exception($e);
        }
    }


    public static function createBillItems($items, $bill){
        DB::beginTransaction();
        try {
            foreach ($items as $item) {
                $zm_item = new ZmBillItem([
                    'zm_bill_id' => $bill->id,
                    'billable_id' => $item['billable_id'],
                    'billable_type' => $item['billable_type'],
                    'use_item_ref_on_pay' => 'N',
                    'tax_type_id' => $item['tax_type_id'],
                    'amount' => $item['amount'],
                    'currency' => $item['currency'],
                    'exchange_rate' => $bill->exchange_rate,
                    'equivalent_amount' => $bill->exchange_rate * $item['amount'],
                    'gfs_code' => $item['gfs_code']
                ]);
                $zm_item->save();
            }
            DB::commit();
            return true;
        } catch (Exception $e){
            DB::rollBack();
            report($e);
            throw new Exception($e);
        }
    }

    /**
     * @param Bill|int $bill Instance of ZmBill or bill id
     * @param string $generated_by
     * @param string $approved_by
     * @return ZmResponse
     * @throws \DOMException
     */
    public static function sendBill($bill, $generated_by = 'ZRB', $approved_by = 'ZRB'): ZmResponse
    {
        $returnable = [
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
            PortReturn::class,
        ];
    
        $multipleBillsReturnable = [
            PortReturn::class,
        ];
    
        $debtReturnable = [
            Debt::class
        ];
    
        $installable = [
            InstallmentItem::class
        ];
    

        if (is_numeric($bill)) {
            $zm_bill = ZmBill::query()->find($bill);
        } else if ($bill instanceof ZmBill) {
            $zm_bill = $bill;
        } else {
            throw new \Exception('Invalid bill supplied to send bill');
        }

        // we have bill, get last control no
        $latest = ZmBill::orderBy('created_at', 'DESC')->first();
        if($latest){
            $bill->control_number = $latest->control_number + 1;
            $bill->zan_trx_sts_code = 7101;
            $bill->save();
        } else {
            $bill->control_number = 2000070000145;
            $bill->zan_trx_sts_code = 7101;
            $bill->save();
        }

        if (in_array($bill->billable_type, array_merge(
            $returnable,
            $multipleBillsReturnable,
            $debtReturnable,
            $installable))) {
            try {
                $billable         = $bill->billable;
                $billable->status = ReturnStatus::CN_GENERATED;
                $billable->save();
            } catch(\Exception $e){
                Log::error($e);
            }
        }

        return;

        $xml = new XmlWrapper('gepgBillSubReq');
        $xml_bill_hdr = $xml->createElement("BillHdr");
        $xml->addChildrenToNode([
            'SpCode' => config('modulesconfig.zm_spcode'),
            'RtrRespFlg' => 'true'
        ], $xml_bill_hdr);

        $xml_trx_info = $xml->createElement("BillTrxInf");

        $xml->addChildrenToNode([
            'BillId' => $zm_bill->id,
            'SubSpCode' => config('modulesconfig.zm_subspcode'),
            'SpSysId' =>  config('modulesconfig.zm_spsysid'),
            'BillAmt' => $zm_bill->amount,
            'MiscAmt' => 0,
            'BillExprDt' => Carbon::createFromFormat('Y-m-d H:i:s', $zm_bill->expire_date)->format('Y-m-d\TH:i:s'),
            'PyrId' => $zm_bill->payer_id,
            'PyrName' => $zm_bill->payer_name,
            'BillDesc' => $zm_bill->description,
            'BillGenDt' => Carbon::now()->format('Y-m-d\TH:i:s'),
            'BillGenBy' => $generated_by,
            'BillApprBy' => $approved_by,
            'PyrCellNum' => self::formatPhone($zm_bill->payer_phone_number),
            'PyrEmail' => $zm_bill->payer_email,
            'Ccy' => $zm_bill->currency,
            'BillEqvAmt' => $zm_bill->amount,
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
                'BillItemAmt' => $zm_item->amount,
                'BillItemEqvAmt' => $zm_item->amount,
                'BillItemMiscAmt' => 0,
                'GfsCode' => $zm_item->gfs_code
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

        $zm_bill->zan_trx_sts_code = $status_code;

        if ($status_code == 7101) {
            $zm_bill->zan_status = 'pending';
        } else {
            $zm_bill->zan_status = 'failed';
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
            if (!$bill->update(['status' => 'cancelled', 'cancellation_reason' => $reason])) {
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
