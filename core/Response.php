<?php

namespace Moonwalker\Core;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Response
{
    private $response;
    private $request;
    private $metadata;

    public function __construct(RequestInterface $request = null, ResponseInterface $response = null, Array $metadata)
    {
        $this->response = is_null($response) ? new \Zend\Diactoros\Response() : $response;
        $this->request = is_null($request) ? null : $request;
        $this->metadata = $metadata;
    }

    public static function with (RequestInterface $request, ResponseInterface $response, Array $metadata = null)
    {
        return new static($request, $response, $metadata);
    }

    public function ok (Array $data)
    {
        $temp = [
            "success" => true, "payload" => $data, "error" => null, '_meta' => $this->metadata
        ];

        return $this->__generate($temp, 200);
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