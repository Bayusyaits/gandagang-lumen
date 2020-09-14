<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Sentry Defaults
    |--------------------------------------------------------------------------
    |
    | capture release as git sha
    | Capture bindings on SQL queries
    |
    */
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'breadcrumbs.sql_bindings' => true,
];
