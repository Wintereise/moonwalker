<?php

namespace Moonwalker\Core;
use Psr\Http\Message\ServerRequestInterface;

class Utility
{
    private static $headers;

    public static function extractIP (ServerRequestInterface $request)
    {
        if (! static::$headers)
            static::$headers = require_once __DIR__ . '../app/Config/reverse-proxy.php';

        $headers = static::$headers;
        $ipAddr = false;

        if (count($headers) == 0)
        {
            $server = $request->getServerParams();
            if (! empty($server['REMOTE_ADDR']) && filter_var($server['REMOTE_ADDR'], FILTER_VALIDATE_IP))
                $ipAddr = $server['REMOTE_ADDR'];
        }
        else
            foreach ($headers as $header)
            {
                $extractedHeader = $request->getHeaderLine($header);
                if (! empty($extractedHeader) && filter_var($extractedHeader, FILTER_VALIDATE_IP))
                    $ipAddr = $extractedHeader;
            }

        return $ipAddr;
    }

    public static function extractJWTToken ($header)
    {
        list ($bearer, $token) = explode(" ", $header);
        return $token;
    }

    public static function isAjax(ServerRequestInterface $request)
    {
        return strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
    }

    public static function isPost(ServerRequestInterface $request)
    {
        switch (strtoupper($request->getMethod()))
        {
            case 'GET':
            case 'HEAD':
            case 'CONNECT':
            case 'TRACE':
            case 'OPTIONS':
            case 'DELETE':
                return false;
        }
        return true;
    }

}