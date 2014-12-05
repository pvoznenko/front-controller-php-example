<?php
namespace app;

use app\interfaces\RouteInterface;
use app\interfaces\RequestInterface;
use app\exceptions\NotFoundException;

/**
 * Class Router
 */
class Router
{

    /**
     * List of routes
     *
     * @var array
     */
    protected $routes = [];

    /**
     * @param array $routes - list of routes
     */
    public function __construct(array $routes)
    {
        $this->addRoutes($routes);
    }

    /**
     * Add route
     *
     * @param RouteInterface $route
     * @return $this
     */
    public function addRoute(RouteInterface $route)
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Add list of routes
     *
     * @param array $routes
     * @return $this
     */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }

        return $this;
    }

    /**
     * Get list of routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Method will search for correct rout for provided request
     *
     * @param RequestInterface $request
     *
     * @throws NotFoundException - if no matched routes for given request was found
     * @return RouteInterface
     */
    public function route(RequestInterface $request)
    {
        /** @var RouteInterface $route */
        foreach ($this->routes as $route) {
            if ($route->match($request)) {
                return $route;
            }
        }

        throw new NotFoundException("No route matched the given URI.");
    }
}