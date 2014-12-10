<?php
namespace App;

use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;
use App\Exceptions\NotFoundException;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotAcceptableException;
use App\Exceptions\UnauthorizedException;

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
     * Method runs requests and handle exceptions, so that exceptions could be rendered with correct HTTP codes
     *
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
            $message = $error->getMessage();
            $errorLog = $message;

            switch (true) {
                case $error instanceof \InvalidArgumentException:
                case $error instanceof BadRequestException:
                    $header = '400 Bad Request';
                    break;
                case $error instanceof UnauthorizedException:
                    $header = '401 Unauthorized';
                    break;
                case $error instanceof NotFoundException:
                    $header = '404 Page Not Found';
                    break;
                case $error instanceof NotAcceptableException:
                    $header = '406 Not Acceptable';
                    break;
                default:
                    $header = '500 Internal Server Error';
                    $errorLog = var_export(['Exception' => $message, 'trace' => $error->getTraceAsString()], true);
            }

            error_log($errorLog);
            $response->addHeader($header)->send(['error' => $message]);
        }
    }
}