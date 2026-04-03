<?php

declare(strict_types=1);

return [
    'enabled' => env('CATCH_MAIL_ENABLED', true),

    'allowed_environments' => ['local', 'staging'],

    'route_prefix' => '__mailbox',

    'middleware' => ['web'],

    'max_body_length' => 500000,

    'enable_polling' => env('CATCH_MAIL_ENABLE_POLLING', true),
    'polling_interval' => env('CATCH_MAIL_POLLING_INTERVAL', 10000),
];
