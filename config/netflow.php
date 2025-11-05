<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NetFlow Listener Port
    |--------------------------------------------------------------------------
    |
    | The UDP port on which the NetFlow listener will receive flow data
    | from network devices.
    |
    */
    'port' => env('NETFLOW_PORT', 9995),

    /*
    |--------------------------------------------------------------------------
    | Data Retention Period
    |--------------------------------------------------------------------------
    |
    | Number of days to retain flow data before automatic cleanup
    |
    */
    'retention_days' => env('NETFLOW_RETENTION_DAYS', 7),

    /*
    |--------------------------------------------------------------------------
    | Traffic Aggregation
    |--------------------------------------------------------------------------
    |
    | Enable automatic aggregation of traffic statistics
    |
    */
    'aggregation_enabled' => env('NETFLOW_AGGREGATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Supported NetFlow Versions
    |--------------------------------------------------------------------------
    |
    | NetFlow versions supported by this analyzer
    |
    */
    'supported_versions' => [5, 9],
];