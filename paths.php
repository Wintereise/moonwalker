<?php

$env = getenv('MOONWALKER_ENV') ? getenv('MOONWALKER_ENV') . '/' : '';
return [
    'routes'                        => __DIR__ . '/app/Routes/routes.php',
    'services'                      => __DIR__ . '/app/services.php',
    'config.logger'                 => __DIR__ . '/app/config/' . $env . 'logger.php',
];