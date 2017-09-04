<?php

namespace Moonwalker\Controllers;

use Moonwalker\Core\Controller;
use Moonwalker\Core\Response;
use Moonwalker\Seeders\DefaultSeed;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MaintenanceController extends Controller
{
    public function seedDatabase (ServerRequestInterface $request, ResponseInterface $response)
    {
        DefaultSeed::seed();
        return Response::with($request, $response)->ok([ "All data has been seeded successfully! "]);
    }
}