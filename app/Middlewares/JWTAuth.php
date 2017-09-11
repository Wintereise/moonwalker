<?php

namespace Moonwalker\Middlewares;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Moonwalker\Core\Errors\UserFriendlyException;;

use Moonwalker\Core\Utility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class JWTAuth
{
    public function __invoke (ServerRequestInterface $request, ResponseInterface $response, Callable $next)
    {
        $token = $request->getHeaderLine("Authorization");

        if (empty($token))
            throw new UserFriendlyException("Your request is missing the JWT auth token in the Authorization header.", 403);

        if (strstr($token, 'Bearer ') === false) //Yes, the space is intentional. Lookup the JWT RFC
            throw new UserFriendlyException("Invalid JWT token specified.", 403);

        $JWTToken = Utility::extractJWTToken($token);

        $JWTConfig = require_once __DIR__ . '../Config/jwt.php';

        $JWTParser = new Parser();
        $parsedToken = $JWTParser->parse($JWTToken);
        $JWTSigner = new $JWTConfig['signing-alg'];
        $JWTSecret = $JWTConfig['signing-key'];

        if ($parsedToken->verify($JWTSigner, $JWTSecret))
        {
            $JWTData = new ValidationData();
            $JWTData->setIssuer($JWTConfig['issuer']);
            $JWTData->setAudience($JWTConfig['audience']);
            $JWTData->setId($parsedToken->getClaim("jti"));

            if (! $parsedToken->validate($JWTData))
                throw new UserFriendlyException("JWT validation failed.", 403);
        }
        else
            throw new UserFriendlyException("JWT verification failed.", 403); // Yes, the errors are slightly different.

        return $next($request, $response);
    }
}