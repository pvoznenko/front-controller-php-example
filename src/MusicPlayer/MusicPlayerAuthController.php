<?php
namespace MusicPlayer;

use app\BaseController;
use app\dataLayer\BaseEntity;
use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;
use MusicPlayer\models\UsersModel;
use app\exceptions\UnauthorizedException;
use app\exceptions\BadRequestException;

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

    /**
     * Method returns page number for pagination
     *
     * @return int
     */
    protected function getPageNumber()
    {
        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();

        $page = isset($requestData[$method]) && isset($requestData[$method]['page']) ? (int)$requestData[$method]['page'] : 1;

        if ($page <= 0) {
            $page = 1;
        }

        return $page;
    }

    /**
     * Method returns array with mandatory information for the pagination
     *
     * @param int $page
     * @param int $numberOfResults
     * @param int $limit - default BaseEntity::DEFAULT_ROWS_LIMIT
     *
     * @return array
     */
    protected function getPaginationBlock($page, $numberOfResults, $limit = BaseEntity::DEFAULT_ROWS_LIMIT)
    {
        return [
            'num_results' => $numberOfResults,
            'limit' => $limit,
            'offset' => BaseEntity::calculateOffset($page, $limit),
            'page' => $page
        ];
    }

    /**
     * Method validates if all specified data presented in request
     *
     * @param array $keys - keys to validate
     * @throws BadRequestException
     */
    protected function validatePresentedData(array $keys)
    {
        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();

        if (!isset($requestData[$method])) {
            throw new BadRequestException;
        }

        foreach ($keys as $key) {
            if (!isset($requestData[$method][$key])) {
                throw new BadRequestException('Variable "' . $key . '"must be specified!');
            }
        }
    }
} 