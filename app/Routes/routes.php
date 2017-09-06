<?php
/** @var \League\Route\RouteCollection $router */

$router->get('/', 'Moonwalker\Controllers\HelloWorldController::sayHello');
$router->post('/', 'Moonwalker\Controllers\HelloWorldController::postHello')
    ->middleware(new \Moonwalker\Middlewares\UnmarshalRequestBody());

$router->group('/v1', function (\League\Route\RouteGroup $router)
{
    $router->get('/users/{id}', 'Moonwalker\Controllers\UserController::getUser');
    $router->get('/users', 'Moonwalker\Controllers\UserController::getUsers');
})
    ->middleware(new \Moonwalker\Middlewares\UnmarshalRequestBody());

$router->group('/maint', function (\League\Route\RouteGroup $router)
{
    $router->get('/seed', 'Moonwalker\Controllers\MaintenanceController::seedDatabase');
    $router->get('/test/permissions/{uid}/{permission}/{target}', 'Moonwalker\Controllers\TestController::validatePermission');
    $router->get('/test/visualize', 'Moonwalker\Controllers\TestController::visualizeRequest');
});


