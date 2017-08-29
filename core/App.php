<?php

namespace Moonwalker\Core;


use \League\Container\Container as Container;
use \Zend\Diactoros\Response\SapiEmitter as SapiEmitter;
use \Zend\Diactoros\Response as Response;
use \Zend\Diactoros\ServerRequestFactory as ServerRequestFactory;
use \League\Route\RouteCollection as RouteCollection;

class App extends Container
{
    private $paths;

    public function __construct (Array $paths)
    {
        parent::__construct();
        $this->paths = $paths;

        $this->share('response', Response::class);
        $this->share('request', function () {
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