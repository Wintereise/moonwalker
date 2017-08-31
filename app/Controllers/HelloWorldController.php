<?php
namespace Moonwalker\Controllers;

use Moonwalker\Core\Controller;
use Moonwalker\Core\Errors\ValidationFailedException;
use Moonwalker\Core\Response;

use Moonwalker\Models\TodoCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class HelloWorldController extends Controller
{
    public function sayHello (ServerRequestInterface $request, ResponseInterface $response)
    {
        $todos = new TodoCollection();
        $todos->where()->greaterThan('id', 20);

        $t = new Response();
        return $t->ok([ $todos->toArray() ]);
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
}