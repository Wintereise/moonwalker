<?php

$env = getenv('MOONWALKER_ENV') ? getenv('MOONWALKER_ENV') . '/' : '';

return [
    'config.generic'                 => __DIR__ . '/app/Config/' . $env . 'generic.php',
    'config.database'                => __DIR__ . '/app/Config/' . $env . 'database.php',
    'routes'                         => __DIR__ . '/app/Routes/routes.php',
    'log'                            => __DIR__ . '/app/Storage/app.log',
];