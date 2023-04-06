<?php

return [
    'oracle' => [
        'driver' => 'oracle',
        'tns' => env('DB_TNS', ''),
        'host' => secEnv('DB_HOST', ''),
        'port' => secEnv('DB_PORT', '1521'),
        'database' => secEnv('DB_DATABASE', ''),
        'service_name' => secEnv('DB_SERVICENAME', ''),
        'username' => secEnv('DB_USERNAME', ''),
        'password' => secEnv('DB_PASSWORD', ''),
        'charset' => env('DB_CHARSET', 'AL32UTF8'),
        'prefix' => env('DB_PREFIX', ''),
        'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
        'edition' => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance' => env('DB_LOAD_BALANCE', 'yes'),
        'dynamic' => [],
        'version' => env('OCI_VERSION', '3.2.1'),
    ],
];
