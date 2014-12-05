<?php
namespace app;

use app\interfaces\RouteInterface;
use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;

/**
 * Class Dispatcher
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