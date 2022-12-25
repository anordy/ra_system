<?php

return [
    //SMS APIs
    'smstestapi' => 'http://172.25.27.66:8784/fasthub/opes/messaging/api',
    'smsliveapi' => '',
    'smsheader' => 'ZanMalipo',

    //BoT Exchange Rates
    'botexapi' => 'https://www.bot.go.tz/services/api/exrates',

    //BIO Websocket
    'bio_websocket' => env('WEBSOCKET_BIO'),

    //ZM
    'zm_cancel' => env('ZAN_MALIPO_URL_CANCEL_BILL'),
    'zm_recon' => env('ZAN_MALIPO_URL_RECON'),
    'zm_update_bill' => env('ZAN_MALIPO_URL_UPDATE_BILL'),
    'zm_create_bill' => env('ZAN_MALIPO_URL_CREATE_BILL'),
    'zm_spcode' => env('ZAN_MALIPO_SP_CODE'),
    'zm_spsysid' => env('ZAN_MALIPO_SP_SYS_ID'),
    'zm_subspcode' => env('ZAN_MALIPO_SUB_SP_CODE'),
    'sp_code' => 'SP20011',

    // API SERVER CREDENTIALS
    'api_url' => env('API_SERVER_URL'),
    'api_server_username' => 'immigration',
    'api_server_password' => 'password',

    // DIP(Data Integrity Protection) Service
    'verification' => [
        'server_url' => env('VERIFICATION_SERVER_URL'),
        'server_username' => env('VERIFICATION_SERVER_USERNAME'),
        'server_password' => env('VERIFICATION_SERVER_PASSWORD'),
        'server_token' => env('VERIFICATION_SERVER_TOKEN')
    ]
]
?>
