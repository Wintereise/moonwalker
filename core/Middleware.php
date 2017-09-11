<?php


namespace Moonwalker\Core;


use League\Route\ContainerAwareInterface;
use League\Route\ContainerAwareTrait;

class Middleware implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /** @var \League\Container\Container $container */
    protected $container;
}