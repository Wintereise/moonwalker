<?php

namespace Moonwalker\Core;
use Psr\Http\Message\ResponseInterface;

class Response
{
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function ok (Array $data)
    {
        return $this->__generate($data, 200);
    }

    private function __generate ($data, $status)
    {
        $this->response->getBody()->write(json_encode($data));
        return $this->response->withStatus($status);
    }
}