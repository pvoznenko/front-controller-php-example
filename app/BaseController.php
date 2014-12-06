<?php
namespace app;

use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;

/**
 * Class BaseController
 * @package app
 *
 * Base Controller
 */
abstract class BaseController
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Method handle execution of controller's action specified in Route object
     *
     * @param string $action - name of method that should be executed
     * @param RequestInterface $request - request object from client
     * @param ResponseInterface $response - response object
     */
    public function execute($action, RequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;

        call_user_func_array([$this, $action], $request->getParams());
    }
}