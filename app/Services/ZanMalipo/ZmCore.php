<?php

namespace App\Services\ZanMalipo;


use Exception;
use Carbon\Carbon;
use App\Models\ZmBill;
use App\Models\ZmBillItem;
use App\Models\ZmEgaCharge;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Api\ZanMalipoInternalService;

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
            if (config('modulesconfig.charges_inclusive')){
                $bill_items = ZmFeeHelper::addTransactionFee($bill_items, $currency, $exchange_rate);
            }

            $egaCharges = ZmFeeHelper::getTransactionFee($bill_items,$currency,$exchange_rate);

            $bill_amount = 0;
            foreach ($bill_items as $item) {
                if (!isset($item['amount']) || !isset($item['gfs_code'])) {
                    throw new \Exception('Bill item must contain amount and gfs_code');
                }
                
                $bill_amount += $item['amount'];
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
                'payer_name' => trim($payer_name),
                'payer_phone_number' => $payer_phone_number,
                'payer_email' => $payer_email,
                'currency' => $currency,
                'description' => $payment_description,
                'payment_option' => $payment_option,
                'status' => 'pending',
                'createdby_id' => $createdby_id,
                'createdby_type' => $createdby_type,
                'origin' => $createdby_type == null ? 'job' : 'user'
            ]);

            $zm_bill->save();

            ZmEgaCharge::create([
                'zm_bill_id' => $zm_bill->id,
                'currency' => $zm_bill->currency,
                'amount' => $egaCharges['amount'],
                'ega_charges_included' => config('modulesconfig.charges_inclusive'),
            ]);

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
                if ($item['amount'] > 0) {
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
    public static function sendBill($bill, $generated_by = 'ZRA', $approved_by = 'ZRA'): ZmResponse
    {
        if (is_numeric($bill)) {
            $zm_bill = ZmBill::query()->find($bill);
        } else if ($bill instanceof ZmBill) {
            $zm_bill = $bill;
        } else {
            throw new \Exception('Invalid bill supplied to send bill');
        }
        $res = (new ZanMalipoInternalService)->createBill($zm_bill);
        return new ZmResponse($res['data']['status_code'], $zm_bill);
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
                'BillExprDt' => Carbon::make($expire_date)->format('Y-m-d\TH:i:s'),
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
