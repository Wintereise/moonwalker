<?php

return [
    'name' => 'moonwalker-api',
    'version' => 0.1,
    'log.retention' => 7, // Retains 7 days' worth of logs, discards older.
    'controllers' => [
        'Moonwalker\Controllers\UserController',
        'Moonwalker\Controllers\HelloWorldController',
        'Moonwalker\Controllers\TestController',
        'Moonwalker\Controllers\MaintenanceController',
    ]
];