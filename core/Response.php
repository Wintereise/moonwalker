<?php

namespace Moonwalker\Core;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Response
{
    private $response;
    private $request;
    private $metadata;

    public function __construct(RequestInterface $request = null, ResponseInterface $response = null, $metadata)
    {
        $this->response = is_null($response) ? new \Zend\Diactoros\Response() : $response;
        $this->request = is_null($request) ? null : $request;
        $this->metadata = $metadata;
    }

    public static function with (RequestInterface $request, ResponseInterface $response, $metadata = null)
    {
        return new static($request, $response, $metadata);
    }

    public function noContent (Array $data)
    {
        return $this->__generate($this->buildSchema($data), 204);
    }

    public function ok (Array $data)
    {
        return $this->__generate($this->buildSchema($data), 200);
    }

    public function created (Array $data)
    {
        return $this->__generate($this->buildSchema($data), 201);
    }

    private function buildSchema (Array $data, $success = true, $error = null)
    {
        return [
            "success" => $success, "payload" => $data, "error" => $error, '_meta' => $this->metadata
        ];
    }

    private function __generate ($data, $status)
    {
        if (! is_null($this->request))
        {
            $contentType = $this->request->getHeader('content-type');
            if (isset($contentType[0]))
            {
                switch ($contentType[0])
                {
                    case "application/json":
                        $this->response->getBody()->write(json_encode($data));
                        break;

                    case "application/xml":
                        $this->response->getBody()->write($this->arrayToXML($data, new \SimpleXMLElement('<response/>')));
                        break;

                    case "application/msgpack":
                    case "application/x-msgpack":
                        $this->response->getBody()->write(msgpack_pack($data));
                        break;

                    default:
                        $this->response->getBody()->write(json_encode($data));
                }
            }
            else
                $this->response->getBody()->write(json_encode($data));
        }

        return $this->response->withStatus($status);
    }

    private function arrayToXML (Array $array, \SimpleXMLElement $xml, $childName = "value")
    {
        foreach ($array as $k => $v)
        {
            if(is_array($v))
            {
                (is_int($k)) ? $this->arrayToXML($v, $xml->addChild($childName), $v) : $this->arrayToXML($v, $xml->addChild(strtolower($k)), $childName);
            }
            else {
                (is_int($k)) ? $xml->addChild($childName, $v) : $xml->addChild(strtolower($k), $v);
            }
        }

        return $xml->asXML();
    }
}