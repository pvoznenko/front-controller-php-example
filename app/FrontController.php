<?php
namespace app;

use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;
use app\exceptions\NotFoundException;
use app\exceptions\BadRequestException;
use app\exceptions\NotAcceptableException;

/**
 * Class FrontController
 */
class FrontController
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param Router $router
     * @param Dispatcher $dispatcher
     */
    public function __construct(Router $router, Dispatcher $dispatcher)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function run(RequestInterface $request, ResponseInterface $response)
    {
        try {
            if (!$response->isRequestedMimeSupported($request)) {
                throw new NotAcceptableException;
            }

            $route = $this->router->route($request);
            $this->dispatcher->dispatch($route, $request, $response);
        } catch (\Exception $error) {
            switch (true) {
                case $error instanceof \InvalidArgumentException:
                case $error instanceof BadRequestException:
                    $header = '400 Bad Request';
                    break;
                case $error instanceof NotFoundException:
                    $header = '404 Page Not Found';
                    break;
                case $error instanceof NotAcceptableException:
                    $header = '406 Not Acceptable';
                    break;
                default:
                    $header = '500 Internal Server Error';
            }

            $message = $error->getMessage();

            error_log($message);
            $response->addHeader($header)->send(['error' => $message]);
        }
    }
}