<?php

namespace app\interfaces;

use app\BaseController;
use app\exceptions\BadRequestException;

/**
 * Interface RouteInterface
 * @package interfaces
 */
interface RouteInterface
{
    /**
     * Checks if a request is match current route and invoke params to the requests if it match
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function match(RequestInterface $request);

    /**
     * Reruns instance of mapped controller
     *
     * @param RequestInterface $request
     *
     * @throws BadRequestException - if controller or method is not exist, or wrong parameters
     *
     * @return BaseController
     */
    public function createController(RequestInterface $request);

    /**
     * Returns name of mapped action
     *
     * @return string
     */
    public function getAction();
} 