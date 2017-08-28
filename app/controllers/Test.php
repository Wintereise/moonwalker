<?php

namespace Moonwalker\Controllers;

use Phprest\Response;
use Phprest\Exception;

use Symfony\Component\HttpFoundation\Request;

class Test
{
    /**
     *
     * @param Request $request
     * @param string $version
     * @param integer $id
     *
     * @return Response\Ok
     *
     * @throws Exception\NotFound
     */

    public function get(Request $request, $version)
    {
        return new Response\Ok("Hello world!");
    }
}