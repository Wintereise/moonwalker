<?php

namespace Moonwalker\Controllers;

use League\Route\Http\Exception\NotFoundException;
use Moonwalker\Core\Controller;
use Moonwalker\Core\Errors\ValidationFailedException;
use Moonwalker\Core\Response;
use Moonwalker\Models\User;
use Moonwalker\Models\UserCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserController extends Controller
{
    public function getUser (ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        if (! $this->validate($args, [
            'integer' => 'id'
        ]))
            throw new ValidationFailedException($this->validator->errors());

        $user = User::findByPrimaryKey($args['id']);

        if (! $user )
            throw new NotFoundException("Not found", null, 404);

        $permissions = $user->permissions->items();

        return Response::with($request, $response)->ok([ 'user' => $user, 'permissions' => $permissions ]);
    }

    public function getUsers (ServerRequestInterface $request, ResponseInterface $response)
    {
        /*
         * Yes, this method is intentionally missing the validation call
         * This is because there is nothing to validate on a / call, notice we do NOT take any parameters from the user
         * Todo: Actually build pagination
         * Todo: Need a postFilter layer to ban certain attributes from being exported to the user
         */
        return Response::with($request, $response)->ok([ (new UserCollection())->toArray() ]);
    }
}