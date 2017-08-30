<?php

namespace Moonwalker\Core\Errors;


class ValidationFailedException extends \Exception
{
    private $data;
    public function __construct($data = null)
    {
        parent::__construct("Your request has failed input validation. Please re-check your input, and try again.", 400, null);
        $this->data = $data;
    }

    public function getData ()
    {
        return $this->data;
    }
}