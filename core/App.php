<?php

namespace Moonwalker\Core;

use \League\Container\Container as Container;
use \League\Route\RouteCollection as RouteCollection;
use League\BooBoo as ErrorHandler;

use Moonwalker\Core\Errors\Formatters\SelectiveErrorFormatter;

use Moonwalker\Core\Errors\Handlers\SelectiveErrorHandler;
use \Zend\Diactoros\Response\SapiEmitter as SapiEmitter;
use \Zend\Diactoros\Response as Response;
use \Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

use Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler as StreamHandler;

class App extends Container
{
    private $paths;
    private $config;

    public function __construct (Array $paths, Array $config)
    {
        parent::__construct();

        $this->paths = $paths;
        $this->config = $config;

        $this->add('Moonwalker\Core\App', function ()
        {
            return $this;
        });

        $this->inflector('League\Route\ContainerAwareInterface')
            ->invokeMethod('setContainer', ['Moonwalker\Core\App']);

        foreach ($config['controllers'] as $controller)
        {
            $this->share($controller);
        }

        $this->share('response', Response::class);
        $this->share('request', function ()
        {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
        $this->share('emitter', SapiEmitter::class);

        $this->share('logger', function ()
        {
            return new Logger('logs');
        });



        $router = new RouteCollection($this);
        $this->share('router', $router);
        require_once $this->paths['routes'];

        $errorHandler = new ErrorHandler\Runner();
        $selectiveFormatter = new SelectiveErrorFormatter();
        $logger = $this->get('logger')->pushHandler(new StreamHandler($paths['log']));
        $logHandler = new SelectiveErrorHandler($logger);
        $errorHandler->pushFormatter($selectiveFormatter);
        $errorHandler->pushHandler($logHandler);
        $errorHandler->register();
    }

    public function run ()
    {
        $response = $this->get('router')->dispatch($this->get('request'), $this->get('response'));
        return $this->get('emitter')->emit($response);
    }

}