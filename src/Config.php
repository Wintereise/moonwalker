<?php

namespace Phprest;

use League\BooBoo\Runner;
use League\Container\Container;
use League\Container\ContainerInterface;
use League\Event\Emitter as EventEmitter;
use League\Event\EmitterInterface as EventEmitterInterface;
use League\Route\Strategy\StrategyInterface;
use Phprest\ErrorHandler\Formatter\JsonXml as JsonXmlFormatter;
use Phprest\ErrorHandler\Handler\Log as LogHandler;
use Phprest\Router\RouteCollection;
use Phprest\Router\Strategy as RouterStrategy;
use Phprest\Service\Hateoas\Config as HateoasConfig;
use Phprest\Service\Hateoas\Service as HateoasService;
use Phprest\Service\Logger\Config as LoggerConfig;
use Phprest\Service\Logger\Service as LoggerService;

class Config
{
    /**
     * @var string
     */
    protected $vendor;

    /**
     * @var string
     */
    protected $apiVersion;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RouteCollection
     */
    protected $router;

    /**
     * @var EventEmitterInterface
     */
    protected $eventEmitter;

    /**
     * @var Runner
     */
    protected $errorHandler;

    /**
     * @var HateoasConfig
     */
    protected $hateoasConfig;

    /**
     * @var HateoasService
     */
    protected $hateoasService;

    /**
     * @var LoggerConfig
     */
    protected $loggerConfig;

    /**
     * @var LoggerService
     */
    protected $loggerService;

    /**
     * @var LogHandler
     */
    protected $logHandler;

    /**
     * @param string $vendor
     * @param string $apiVersion
     * @param bool $debug
     */
    public function __construct($vendor, $apiVersion, $debug = false)
    {
        if (! preg_match('#^' . Application::API_VERSION_REG_EXP . '$#', (string) $apiVersion)) {
            throw new \InvalidArgumentException('Api version is not valid');
        }

        $this->vendor       = $vendor;
        $this->apiVersion   = $apiVersion;
        $this->debug        = $debug;

        $this->setContainer(new Container());
        $this->setRouter(new RouteCollection($this->getContainer()));
        $this->setEventEmitter(new EventEmitter());
        $this->setHateoasConfig(new HateoasConfig($debug));
        $this->setHateoasService(new HateoasService());
        $this->setLoggerConfig(new LoggerConfig('phprest'));
        $this->setLoggerService(new LoggerService());
        $this->setLogHandler(new LogHandler());
        $this->setRouterStrategy(new RouterStrategy($this->getContainer()));

        $errorHandler = new Runner([new JsonXmlFormatter($this)]);
        $errorHandler->silenceAllErrors(false);
        $errorHandler->treatErrorsAsExceptions(true);

        $this->setErrorHandler($errorHandler);
    }

    /**
     * @return string
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param RouteCollection $router
     */
    public function setRouter(RouteCollection $router)
    {
        $this->router = $router;
    }

    /**
     * @param StrategyInterface $strategy
     */
    public function setRouterStrategy(StrategyInterface $strategy)
    {
        $this->router->setStrategy($strategy);
    }

    /**
     * @return RouteCollection
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param EventEmitterInterface $eventEmitter
     */
    public function setEventEmitter(EventEmitterInterface $eventEmitter)
    {
        $this->eventEmitter = $eventEmitter;
    }

    /**
     * @return EventEmitterInterface
     */
    public function getEventEmitter()
    {
        return $this->eventEmitter;
    }

    /**
     * @param Runner $errorHandler
     */
    public function setErrorHandler(Runner $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    /**
     * @return Runner
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * @param HateoasConfig $config
     */
    public function setHateoasConfig(HateoasConfig $config)
    {
        $this->hateoasConfig = $config;
    }

    /**
     * @return HateoasConfig
     */
    public function getHateoasConfig()
    {
        return $this->hateoasConfig;
    }

    /**
     * @param HateoasService $service
     */
    public function setHateoasService(HateoasService $service)
    {
        $this->hateoasService = $service;
    }

    /**
     * @return HateoasService
     */
    public function getHateoasService()
    {
        return $this->hateoasService;
    }

    /**
     * @param LoggerConfig $config
     */
    public function setLoggerConfig(LoggerConfig $config)
    {
        $this->loggerConfig = $config;
    }

    /**
     * @return LoggerConfig
     */
    public function getLoggerConfig()
    {
        return $this->loggerConfig;
    }

    /**
     * @param LoggerService $service
     */
    public function setLoggerService(LoggerService $service)
    {
        $this->loggerService = $service;
    }

    /**
     * @return LoggerService
     */
    public function getLoggerService()
    {
        return $this->loggerService;
    }

    /**
     * @param LogHandler $logHandler
     */
    public function setLogHandler(LogHandler $logHandler)
    {
        $this->logHandler = $logHandler;
    }

    /**
     * @return LogHandler
     */
    public function getLogHandler()
    {
        return $this->logHandler;
    }
}
