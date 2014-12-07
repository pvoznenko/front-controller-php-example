<?php
namespace MusicPlayer;

use app\BaseController;
use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;
use MusicPlayer\models\UsersModel;
use app\exceptions\UnauthorizedException;

/**
 * Class MusicPlayerAuthController
 * @package MusicPlayer
 *
 * Controller responsible for user authorization check
 */
abstract class MusicPlayerAuthController extends BaseController
{
    /**
     * Authorized user id
     *
     * @var int
     */
    protected $userId;

    /**
     * Method handle execution of controller's action specified in Route object
     * Will check if auth token exists and it correct
     *
     * @param string $action - name of method that should be executed
     * @param RequestInterface $request - request object from client
     * @param ResponseInterface $response - response object
     * @throws UnauthorizedException - if user is not authorized
     */
    public function execute($action, RequestInterface $request, ResponseInterface $response)
    {
        $requestData = $request->getRawData();

        if (!isset($requestData['HTTP_TOKEN'])) {
            throw new UnauthorizedException('auth token required!');
        }

        $token = $requestData['HTTP_TOKEN'];
        $userId = (new UsersModel)->getUserIdByToken($token);

        if (!$userId) {
            throw new UnauthorizedException('auth token wrong!');
        }

        $this->userId = $userId;

        parent::execute($action, $request, $response);
    }
} 