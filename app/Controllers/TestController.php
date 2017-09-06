<?php

namespace Moonwalker\Controllers;

use Moonwalker\Core\Controller;
use Moonwalker\Core\PermissionManager;
use Moonwalker\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends Controller
{
    /*
     * Yes, no validation, bite me ;V
     * Access it like this /maint/test/permissions/<user_id>/<urlencoded permission name, i.e: users%edit/<target>
     */
    public function validatePermission (ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        $permission = str_replace('%', '.', $args['permission']);
        $data = PermissionManager::with($args['uid'])->verify($permission, $args['target']);
        return Response::with($request, $response)->ok([ $data ]);
    }

    public function visualizeRequest (ServerRequestInterface $request, ResponseInterface $response)
    {
        $body = $request->getBody();
        $parsedBody = $request->getParsedBody();
        $queryParameters = $request->getQueryParams();
        $attributes = $request->getAttributes();
        $headers = $request->getHeaders();

        return Response::with($request, $response)->ok([
            'body' => $body,
            'parsedBody' => $parsedBody,
            'queryParameters' => $queryParameters,
            'attributes' => $attributes,
            'headers' => $headers
        ]);
    }
}