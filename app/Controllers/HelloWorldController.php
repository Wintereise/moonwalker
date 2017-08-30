<?php
namespace Moonwalker\Controllers;

use Moonwalker\Core\Controller;
use Moonwalker\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HelloWorldController extends Controller
{
    public function sayHello (ServerRequestInterface $request, ResponseInterface $response)
    {
        var_dump($this->container->get('validator'));
        $t = new Response($response);
        return $t->ok([ "Hello world!" ]);
    }

    public function postHello (ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();
        if ($this->validate($data, [
            'required' => 'glossary'
        ]))
        {
            $msg = 'All good!';
        }
        else
        {
            $msg = 'Malformed input: ';
            foreach ($this->validator->errors() as $field => $error)
            {
                $msg .= "$error[0]. ";
            }
        }


        $t = new Response($response);
        return $t->ok([ $msg ]);
    }
}