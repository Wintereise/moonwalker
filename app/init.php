<?php

require_once __DIR__ . '/../vendor/autoload.php';
$paths = require __DIR__ . '/../paths.php';
$template = require_once __DIR__ . '/config/generic.php';

$config = new \Phprest\Config($template['vendor'], $template['debug'], $template['apiVersion']);
$app = new \Phprest\Application($config);

require_once $paths['services'];
require_once $paths['config.logger'];
require_once $paths['routes'];

return $app;