<?php

namespace Moonwalker\Core;

use \League\Container\Container as Container;
use \League\Route\RouteCollection as RouteCollection;

use \Zend\Diactoros\Response\SapiEmitter as SapiEmitter;
use \Zend\Diactoros\Response as Response;
use \Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;

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

        $router = new RouteCollection($this);
        $this->share('router', $router);

        require_once $this->paths['routes'];
    }

    public function run ()
    {
        $response = $this->get('router')->dispatch($this->get('request'), $this->get('response'));
        return $this->get('emitter')->emit($response);
    }

}