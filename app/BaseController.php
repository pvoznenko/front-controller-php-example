<?php
namespace App;

use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;

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
     * @var RequestInterface
     */
    protected $request;

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
        $this->request = $request;

        call_user_func_array([$this, $action], $request->getParams());
    }
}