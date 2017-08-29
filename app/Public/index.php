<?php

require_once __DIR__ . '/../../vendor/autoload.php';
$paths = require_once __DIR__ . '/../../paths.php';

$app = new Moonwalker\Core\App($paths);
$app->run();