<?php
use Phprest\Service\Logger\Config as LoggerConfig;
use Monolog\Handler\StreamHandler;

$template = require_once __DIR__ . "/../config/generic.php";

/** @var \Phprest\Application $app */
/** @var \Monolog\Logger $logger */

$logger = $app->getContainer()->get(LoggerConfig::getServiceName());

$logger->pushHandler(
    new StreamHandler(__DIR__ . '/../storage/' . $template['vendor'] . '.log', \Monolog\Logger::DEBUG)
);