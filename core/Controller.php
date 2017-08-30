<?php

namespace Moonwalker\Core;

use League\Route\ContainerAwareInterface;
use League\Route\ContainerAwareTrait;
use Valitron\Validator;

class Controller implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var \League\Container\Container $container */
    protected $container;

    /** @var \Valitron\Validator $validator */
    protected $validator;

    public function __construct()
    {

    }

    public function validate (Array $data, Array $rules)
    {
        if (is_null($this->validator))
            $this->validator = new Validator($data);
        else
            $this->validator->withData($data);

        $this->validator->rules($rules);

        return $this->validator->validate();
    }
}