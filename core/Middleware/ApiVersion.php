<?php

namespace Phprest\Middleware;

use Negotiation\FormatNegotiator;
use Phprest\Application;
use Phprest\HttpFoundation\Request;
use Phprest\Util;
use Symfony\Component\HttpFoundation\Request as BaseRequest;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ApiVersion implements HttpKernelInterface
{
    use Util\Mime;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param BaseRequest $request
     * @param int $type
     * @param bool $catch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(BaseRequest $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $request        = new Request($request);
        $mimeProcResult = $this->processMime(
            (new FormatNegotiator())->getBest($request->headers->get('Accept', '*/*'))->getValue()
        );

        $request->setApiVersion(
            str_pad($mimeProcResult->apiVersion, 3, '.0')
        );

        return $this->app->handle($request, $type, $catch);
    }

    /**
     * Returns the DI container.
     *
     * @return \League\Container\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->app->getConfiguration()->getContainer();
    }
}
