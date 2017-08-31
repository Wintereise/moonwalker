<?php

namespace Moonwalker\Middlewares;

use Moonwalker\Core\Errors\UserFriendlyException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class UnmarshalRequestBody
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Callable $next)
    {
        $contentType = $request->getHeader('content-type');
        $body = $request->getBody()->getContents();

        if (isset($contentType[0]))
        {
            switch ($contentType[0])
            {
                case "application/json":
                    $parsedBody = json_decode($body, true);

                    if (json_last_error() == JSON_ERROR_NONE)
                        return $next($request->withParsedBody($parsedBody), $response);
                    else
                        throw new UserFriendlyException("Unable to unmarshal JSON, please check your request body.", 400);
                    break;

                case "application/xml":
                    /*
                     * We pick up PHP's E_WARNING as an ErrorException, this leads to cascading failure without the @ sign for simplexml_load_string on malformed XML
                     * simplexml_load_string discards the root tag when returning as array, so we wrap it in __MWCDATA__ tags
                     * This means that XML headers are not acceptable, don't add things like '<?xml version="1.0" encoding="UTF-8" ?>' when requesting
                     * Just send us the data
                     */
                    $parsedBody = @simplexml_load_string('<__MWCDATA__>' . $body . '</__MWCDATA__>'); //
                    if ($parsedBody !== false)
                    {
                        $arrayRepresentation = json_encode($parsedBody);
                        if (json_last_error() == JSON_ERROR_NONE)
                            return $next($request->withParsedBody(json_decode($arrayRepresentation, true)), $response);
                        else
                            $xmlError = true;
                    }
                    else
                        $xmlError = true;

                    if ($xmlError)
                        throw new UserFriendlyException("Unable to unmarshal XML, please check your request body.", 400);
                    break;

                case "application/msgpack":
                case "application/x-msgpack":
                    if (!extension_loaded('msgpack'))
                        throw new UserFriendlyException("This API does not have MSGPACK support enabled.", 400);

                    $arrayRepresentation = msgpack_unpack($body);
                    if (is_array($arrayRepresentation))
                        return $next($request->withParsedBody($arrayRepresentation), $response);
                    else
                        throw new UserFriendlyException("Unable to unmarshal MSGPACK, please check your request body.", 400);
                    break;
            }
        }

        return $next($request, $response);
    }
}