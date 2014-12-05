<?php
namespace app;

use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;

abstract class BaseController
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function execute($action, RequestInterface $request, ResponseInterface $response)
    {
        $this->response = $response;

        call_user_func_array([$this, $action], $request->getParams());
    }
}