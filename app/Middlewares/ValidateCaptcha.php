<?php


namespace Moonwalker\Middlewares;

use Moonwalker\Core\Errors\UserFriendlyException;
use Moonwalker\Core\Middleware;
use Moonwalker\Core\Utility;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReCaptcha\ReCaptcha;

class ValidateCaptcha extends Middleware
{
    public function __invoke (ServerRequestInterface $request, ResponseInterface $response, Callable $next)
    {
        $captchaType = $request->getHeaderLine('MWC-Captcha-Type');
        $captchaToken = $request->getHeaderLine('MWC-Captcha-Token');

        if (empty($captchaToken) || empty($captchaType))
            throw new UserFriendlyException("Your request was to an endpoint protected by captcha, yet it was missing the MWC-Captcha-Type or MWC-Captcha-Token headers.", 403);

        $clientIP = Utility::extractIP($request);
        $captchaProviders = require_once __DIR__ . '../Config/captcha.php';

        switch (strtoupper($captchaType))
        {
            case "RECAPTCHA":
                if (! isset($captchaProviders['RECAPTCHA']))
                    throw new UserFriendlyException('RECAPTCHA is not enabled on this API.', 400);

                $recaptcha = new ReCaptcha($captchaProviders['RECAPTCHA']);
                if (! $recaptcha->verify($captchaToken, $clientIP))
                    throw new UserFriendlyException("Your request has failed RECAPTCHA verification.", 403);
                break;

            default:
                throw new UserFriendlyException("You requested a CAPTCHA provider that does not exist.", 400);
        }

        return $next($request, $response);
    }
}