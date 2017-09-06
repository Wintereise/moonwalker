<?php

namespace Moonwalker\Core\Errors\Formatters;

use League\BooBoo\Formatter\AbstractFormatter;
use League\Route\Http\Exception\HttpExceptionInterface;
use Moonwalker\Core\Errors\UserFriendlyException;
use Moonwalker\Core\Errors\ValidationFailedException;

class SelectiveErrorFormatter extends AbstractFormatter
{
    public function format($e)
    {
        /** @var \Exception $e */
        if ($e->getCode() != 0)
            $code = $e->getCode();
        else
            $code = 500;

        http_response_code($code);

        $why = null;

        if ($e instanceof HttpExceptionInterface || $e instanceof UserFriendlyException || $e instanceof ValidationFailedException)
        {
            $message = $e->getMessage();
            if (method_exists($e, 'getData'))
                $why = $e->getData();
        }
        else
            $message = "We're sorry, something appears to have gone wrong. Please try again later.";

        return json_encode([ "success" => false, "payload" => null, "error" => [ "code" => $code, "message" => $message, 'verbose' => $why ]  ]);
    }
}