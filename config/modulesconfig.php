<?php

return [
    //SMS APIs
    'sms_url' => secEnv('SMS_URL'),
    'smsheader' => secEnv('SMS_HEADER'),
    'sms_password' => secEnv('SMS_PASSWORD'),
    'sms_channel' => secEnv('SMS_CHANNEL'),

    //BoT Exchange Rates
    'botexapi' => 'https://www.bot.go.tz/services/api/exrates',

    //BIO Websocket
    'bio_websocket' => env('WEBSOCKET_BIO'),

    // APP URLs
    'admin_url' => env('ADMIN_URL'),
    'taxpayer_url' => env('TAXPAYER_URL'),

    //ZM
    'zm_cancel' => env('ZAN_MALIPO_URL_CANCEL_BILL'),
    'zm_recon' => env('ZAN_MALIPO_URL_RECON'),
    'zm_update_bill' => env('ZAN_MALIPO_URL_UPDATE_BILL'),
    'zm_create_bill' => env('ZAN_MALIPO_URL_CREATE_BILL'),
    'zm_spcode' => env('ZAN_MALIPO_SP_CODE'),
    'zm_spsysid' => env('ZAN_MALIPO_SP_SYS_ID'),
    'zm_subspcode' => env('ZAN_MALIPO_SUB_SP_CODE'),
    'sp_code' => 'SP20011',

    // Charges
    'charges_inclusive' => false,

    // API SERVER CREDENTIALS
    'api_url' => secEnv('API_SERVER_URL'),
    'api_server_username' => secEnv('API_SERVER_USERNAME'),
    'api_server_password' => secEnv('API_SERVER_PASSWORD'),

    //VFMS API SERVER CREDENTIALS
    'vfms_api_server_username' => secEnv('VFMS_API_SERVER_USERNAME'),
    'vfms_api_server_password' => secEnv('VFMS_API_SERVER_PASSWORD'),

    // DIP(Data Integrity Protection) Service

    'verification' => [
        'server_auth_url' => secEnv('VERIFICATION_SERVER_AUTH_URL'),
        'server_sign_url' => secEnv('VERIFICATION_SERVER_SIGN_URL'),
        'server_verify_url' => secEnv('VERIFICATION_SERVER_VERIFY_URL'),
        'server_username' => secEnv('VERIFICATION_SERVER_USERNAME'),
        'server_password' => secEnv('VERIFICATION_SERVER_PASSWORD'),
        'server_token' => secEnv('VERIFICATION_SERVER_TOKEN')
    ],

    'jasper' => [
        'JSP_URL' => env('JSP_URL'),
        'JSP_USER' => env('JSP_USER'),
        'JSP_PASSWORD' => env('JSP_PASSWORD'),
    ],

    // Web Bulk SMS
    'wb_sms_url' => env('WB_SMS_URL'),
    'wb_smsheader' => env('WB_SMS_HEADER'),
    'wb_sms_password' => env('WB_SMS_PASSWORD'),
    'wb_sms_channel' => env('WB_SMS_CHANNEL'),

    // SMS Provider
    'active_sms_provider' => env('ACTIVE_SMS_PROVIDER'),

    'enable_zm' => env('ENABLE_ZM', false),
    'enable_verification' => env('ENABLE_VERIFICATION', false),
]
?>
