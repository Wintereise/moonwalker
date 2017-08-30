<?php

namespace Moonwalker\Core\Errors\Handlers;

use League\Route\Http\Exception\HttpExceptionInterface;
use League\BooBoo\Handler\HandlerInterface;
use \Psr\Log\LoggerInterface;

use Moonwalker\Core\Errors\UserFriendlyException;
use Moonwalker\Core\Errors\ValidationFailedException;

class SelectiveErrorHandler implements HandlerInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(\Exception $e)
    {
        if ($e instanceof \ErrorException)
        {
            $this->handleErrorException($e);
            return;
        }

        if ($e instanceof HttpExceptionInterface || $e instanceof UserFriendlyException || $e instanceof ValidationFailedException)
            return;

        $this->logger->critical($e->getMessage() . $e->getTraceAsString());
    }

    protected function handleErrorException(\ErrorException $e)
    {
        switch ($e->getSeverity()) {

            case E_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_PARSE:
                $this->logger->error($e->getMessage() . $e->getTraceAsString());
                break;

            case E_WARNING:
            case E_USER_WARNING:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
                $this->logger->warning($e->getMessage() . $e->getTraceAsString());
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $this->logger->notice($e->getMessage() . $e->getTraceAsString());
                break;

            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                $this->logger->info($e->getMessage() . $e->getTraceAsString());
                break;
        }

        return true;
    }
}