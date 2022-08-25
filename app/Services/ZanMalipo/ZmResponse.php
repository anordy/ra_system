<?php

namespace App\Services\ZanMalipo;

class ZmResponse
{

    const SUCCESS = 7101;
    const FAILED_COMMUNICATION_ERROR = 1002;
    const FAILED_SIGNATURE_ERROR = 1003;
    const FAILED_NO_RESPONSE = 1004;
    const FAILED_CHECK_BILL_ITEMS = 1005;
    const FAILED_INVALID_AMOUNT = 1006;
    const FAILED_DB_UPDATE_ERROR = 5001;

    const ZM_BILL_CANCELLED = 7283;
    const ZM_FAILURE = 7201;
    const ZM_REQUIRED_HEADER_MISSING = 7202;
    const ZM_UNAUTHORIZED = 7203;
    const ZM_BILL_DOES_NOT_EXIST = 7204;
    const ZM_INVALID_SP = 7205;
    const ZM_INACTIVE_SP = 7206;

    const ZM_BILL_EXPIRED = 7213;
    const ZM_INVALID_REQUEST_DATA = 7242;
    const ZM_NO_PAYER_EMAIL_OR_PHONE = 7217;
    const ZM_WRONG_PAYER_ID = 7218;
    const ZM_WRONG_CURRENCY = 7219;
    const ZM_INACTIVE_SUB_SP = 7220;
    const ZM_WRONG_BILL_EQUIVALENT_AMOUNT = 7221;
    const ZM_WRONG_BILL_MISC_AMOUNT = 7222;
    const ZM_INVALID_GFS_OR_SERVICE = 7223;
    const ZM_WRONG_BILL_AMOUNT = 7224;
    const ZM_INVALID_BILL_REF_NO = 7225;
    const ZM_DUPLICATE_BILL_INFO = 7226;
    const ZM_INVALID_BILL_ID_NUMBER = 7227;
    const ZM_INVALID_SP_CODE = 7228;
    const ZM_WRONG_BILL_ITEM_GFS= 7229;
    const ZM_WRONG_BILL_GENERATION_DATE = 7230;
    const ZM_WRONG_BILL_EXPIRY_DATE = 7231;
    const ZM_WRONG_BILL_PAYMENT_OPTION = 7234;
    const ZM_INVALID_BILLED_AMOUNT = 7255;
    //Invalid item billed amount
    const ZM_INVALID_ITEM_EQUIVALENT_AMOUNT = 7256;
    const ZM_INVALID_ITEM_MISC_AMOUNT = 7257;
    const ZM_INVALID_EMAIL = 7267;
    const ZM_INVALID_PHONE = 7268;
    const ZM_BILL_CANCELLATION_FAILED = 7286;
    const ZM_INVALID_SIGNATURE = 7303;
    const ZM_INVALID_RECON_REQUEST_DATE = 7318;
    const ZM_RECON_REQUEST_DATE_OUT_OF_RANGE = 7319;
    const ZM_INVALID_RECON_REQUEST_OPTION = 7320;

    public $bill;
    public $status;

    public function __construct($status,$bill)
    {
        $this->bill = $bill;
        $this->status = $status;
    }
}
