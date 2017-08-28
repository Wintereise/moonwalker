<?php

namespace Phprest\Exception;

use Symfony\Component\HttpFoundation\Response;

class Unauthorized extends Exception
{
    /**
     * @param int $code
     * @param array $details
     * @param string $message
     * @param \Exception $previous
     */
    public function __construct(
        $code = 0,
        array $details = [],
        $message = 'Unauthorized',
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, Response::HTTP_UNAUTHORIZED, $details, $previous);
    }
}
