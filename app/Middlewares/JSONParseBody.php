<?php

namespace Moonwalker\Middlewares;

use Moonwalker\Core\Errors\UserFriendlyException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class JSONParseBody
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Callable $next)
    {
        if ($request->getHeader('content-type')[0] == "application/json")
        {
            $body = $request->getBody()->getContents();
            $parsedBody = json_decode($body, true);

            if (json_last_error() == JSON_ERROR_NONE)
                return $next($request->withParsedBody($parsedBody), $response);
            else
                throw new UserFriendlyException("Unable to deserialize JSON, please check your request body.", 400);
        }

        return $next($request, $response);
    }
}