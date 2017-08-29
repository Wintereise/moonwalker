<?php
/** @var \League\Route\RouteCollection $router */

$router->get('/', 'Moonwalker\Controllers\HelloWorldController::sayHello');