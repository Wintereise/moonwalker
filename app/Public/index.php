<?php

require_once __DIR__ . '/../../vendor/autoload.php';
$paths = require_once __DIR__ . '/../../paths.php';
$config = require_once $paths['config.generic'];

$app = new Moonwalker\Core\App($paths, $config);
$app->run();