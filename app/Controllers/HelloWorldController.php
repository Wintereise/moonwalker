<?php
namespace Moonwalker\Controllers;

use Moonwalker\Core\Controller;
use Moonwalker\Core\Errors\ValidationFailedException;
use Moonwalker\Core\PermissionManager;
use Moonwalker\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class HelloWorldController extends Controller
{
    public function sayHello (ServerRequestInterface $request, ResponseInterface $response)
    {
        return Response::with($request, $response)->ok([ 'Hello world!' ]);
    }

    public function postHello (ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        if (! $this->validate($data, [
            'required' => 'glossary'
        ]))
            throw new ValidationFailedException($this->validator->errors());

        return Response::with($request, $response)->ok([ 'All good!' ]);
    }

    public function runTest (ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = PermissionManager::with(3)->verify('users.view', '2');
        return Response::with($request, $response)->ok([ $data ]);
    }
}