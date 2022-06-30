<?php

return [
    //SMS APIs
    'smstestapi' => 'https://uat.ubx.co.tz:8888/ESBConnector/public/api/connector/process/sms',
    'smsliveapi' => '',
    'smsheader' => 'UmojaMobile',

    //BoT Exchange Rates
    'botexapi' => 'https://www.bot.go.tz/services/api/exrates',

    //ZM
    'zm_cancel' => env('ZAN_MALIPO_URL_CANCEL_BILL'),
    'zm_recon' => env('ZAN_MALIPO_URL_RECON'),
    'zm_update_bill' => env('ZAN_MALIPO_URL_UPDATE_BILL'),
    'zm_create_bill' => env('ZAN_MALIPO_URL_CREATE_BILL'),
    'zm_spcode' => env('ZAN_MALIPO_SP_CODE'),
    'zm_spsysid' => env('ZAN_MALIPO_SP_SYS_ID'),
    'zm_subspcode' => env('ZAN_MALIPO_SUB_SP_CODE'),
]
?>
