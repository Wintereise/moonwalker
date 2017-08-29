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
        $this->response->getBody()->write(json_encode($data));
        $this->response->withStatus(200);

        return $this->response;
    }
}