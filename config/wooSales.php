<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WooCommerce Database Connection
    |--------------------------------------------------------------------------
    |
    | The database connection name to use for WooCommerce queries.
    | This should be configured in your database.php config file.
    |
    */
    'database_connection' => env('DB_CONNECTION', 'woocommerce'),

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    |
    | Maximum number of items for client-side export before switching to
    | server-side export.
    |
    */
    'max_client_export' => env('MAX_CLIENT_EXPORT', 1500),

    /*
    |--------------------------------------------------------------------------
    | Table Prefix
    |--------------------------------------------------------------------------
    |
    | The WordPress table prefix used in your WooCommerce installation.
    |
    */
    'table_prefix' => env('DB_TABLE_PREFIX', 'wp_'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Number of items per page in the DataTable.
    |
    */
    'items_per_page' => env('ITEMS_PER_PAGE', 25),
]; 