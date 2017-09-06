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
    private $_rules;

    public function __construct()
    {
        $this->_rules = [
            'required' => [
                ['first_name'], ['last_name'], ['email'], ['password']
            ],
            'email' => 'email',
            'optional' => [
                ['phone'], ['timezone'], ['language']
            ],
            'lengthBetween' => [
                ['password', 5, 72]
            ],
            'slug' => [
                ['phone'], ['timezone'], ['language']
            ],
        ];
    }

    public function createUser (ServerRequestInterface $request, ResponseInterface $response)
    {
        $data = $request->getParsedBody();

        if (! $this->validate($data, $this->_rules))
            throw new ValidationFailedException($this->validator->errors());

        $user = User::createAndLoad($this->cherryPick($data, $this->_rules));

        return Response::with($request, $response)->created([ $user ]);
    }

    public function getUser (ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        if (! $this->validate($args, [
            'integer' => 'id'
        ]))
            throw new ValidationFailedException($this->validator->errors());


        $user = User::findByPrimaryKey($args['id']);

        if (! $user )
            throw new NotFoundException("Not found", null, 404);

        return Response::with($request, $response)->ok([ $user ]);
    }

    public function getUsers (ServerRequestInterface $request, ResponseInterface $response)
    {
        /*
         * Yes, this method is intentionally missing the validation call
         * This is because there is nothing to validate on a / call, notice we do NOT take any parameters from the user
         * Todo: Need a postFilter layer to ban certain attributes from being exported to the user
         */

        $collection = new UserCollection();
        $meta = $this->paginate($request->getQueryParams(), $collection);

        return Response::with($request, $response, $meta)->ok([ $collection->toArray() ]);
    }

    public function updateUser (ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        $this->validateId($args);

        $data = $request->getParsedBody();
        if (! $this->validate($data, $this->_rules))
            throw new ValidationFailedException($this->validator->errors());

        /** @var \Moonwalker\Models\User $user */
        $user = User::findByPrimaryKey($args['id']);
        if (! $user)
            throw new NotFoundException("Not found", null, 404);

        $user->setData($this->cherryPick($data, $this->_rules));
        $user->save();

        return Response::with($request, $response)->ok([ $user ]);
    }

    /*
     * Todo: Test to see if a deletion like this automatically nukes the entries in the defined relations' tables too
     */

    public function deleteUser (ServerRequestInterface $request, ResponseInterface $response, Array $args)
    {
        $this->validateId($args);

        /** @var \Moonwalker\Models\User $user */
        $user = User::findByPrimaryKey($args['id']);
        if (! $user)
            throw new NotFoundException("Not found", null, 404);

        $user->delete();

        return Response::with($request, $response)->ok([ null ]);
    }
}