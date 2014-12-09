<?php
namespace App;

use App\Interfaces\RouteInterface;
use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;

/**
 * Class Dispatcher
 *
 * Dispatcher for Route, Request and Response objects.
 */
class Dispatcher
{

    /**
     * @param RouteInterface $route
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function dispatch(RouteInterface $route, RequestInterface $request, ResponseInterface $response)
    {
        $controller = $route->createController($request);
        $controller->execute($route->getAction(), $request, $response);
    }
}