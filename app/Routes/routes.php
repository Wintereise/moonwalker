<?php
/** @var \League\Route\RouteCollection $router */

$router->get('/', 'Moonwalker\Controllers\HelloWorldController::sayHello');
$router->post('/', 'Moonwalker\Controllers\HelloWorldController::postHello')
    ->middleware(new \Moonwalker\Middlewares\JSONParseBody());