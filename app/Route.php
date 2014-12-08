<?php
namespace app;

use app\interfaces\RequestInterface;
use app\interfaces\RouteInterface;
use app\exceptions\BadRequestException;

/**
 * Class Route
 *
 * Route class ties a method \ uri path to a given action controller.
 */
class Route implements RouteInterface
{
    /**
     * Default controller action name
     */
    const DEFAULT_ACTION = 'index';

    /**
     * Request method, like GET, POST, PUT, DELETE
     *
     * @var string
     */
    protected $method = '';

    /**
     * URL path (supports regexp)
     * @var string
     */
    protected $path = '';

    /**
     * Controller Class
     *
     * @var string
     */
    protected $controllerClass = '';

    /**
     * Controller action
     *
     * @var string
     */
    protected $action = self::DEFAULT_ACTION;

    /**
     * @param string $method - request method, like GET, POST, PUT, DELETE
     * @param string $path - request url path (supports regexp)
     * @param string $controllerClass - controller class
     * @param string $action - controller action, default action is index. I chose to specify action method instead of
     *                         parsing URI and figure out action automatically, because in this way you need less manipulation
     *                         on data. Maybe it is less flexible but in case of load more strait forward.
     */
    public function __construct($method, $path, $controllerClass, $action = self::DEFAULT_ACTION)
    {
        $this->method = $method;
        $this->path = $path;
        $this->controllerClass = $controllerClass;
        $this->action = $action;
    }

    /**
     * Returns action name
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Checks if a request is match current route and invoke params to the requests if it match
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function match(RequestInterface $request)
    {
        $pattern = sprintf('%s %s', $this->method, $this->path);
        $match = sprintf('%s %s', $request->getMethod(), $request->getUri());

        if (preg_match('#^' . $pattern . '$#', $match, $params)) {
            /** since php is invokes objects by link, we can modify request object */
            $request->setParams($params);

            return true;
        }

        return false;
    }

    /**
     * Returns instance of specified controller
     *
     * @param RequestInterface $request
     *
     * @throws BadRequestException - if controller or method is not exist, or wrong parameters
     *
     * @return BaseController
     */
    public function createController(RequestInterface $request)
    {
        $this->validateRequest($request);

        return new $this->controllerClass;
    }

    /**
     * Validates requested URI if controller and method exist and parameters are there
     *
     * @param RequestInterface $request
     *
     * @throws BadRequestException - if controller or method is not exist, or wrong parameters
     */
    private function validateRequest(RequestInterface $request)
    {
        if (!class_exists($this->controllerClass)) {
            throw new BadRequestException(sprintf('Controller class %s not found', $this->controllerClass));
        }

        try {
            $class = new \ReflectionClass($this->controllerClass);
            $method = $class->getMethod($this->action);

            if (count($request->getParams()) < $method->getNumberOfRequiredParameters()) {
                throw new BadRequestException(sprintf('%s::%s wrong amount of required parameters', $this->controllerClass, $this->action));
            }
        } catch (\ReflectionException $exception) {
            throw new BadRequestException(sprintf('Controller class %s has no %s method', $this->controllerClass, $this->action));
        }
    }
}