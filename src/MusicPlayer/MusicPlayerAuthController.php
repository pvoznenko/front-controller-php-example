<?php
namespace MusicPlayer;

use App\BaseController;
use App\Containers\UserDataContainer;
use App\DataLayer\BaseEntity;
use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;
use MusicPlayer\Models\UsersModel;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\BadRequestException;

/**
 * Class MusicPlayerAuthController
 * @package MusicPlayer
 *
 * Controller responsible for user authorization check
 */
abstract class MusicPlayerAuthController extends BaseController
{
    /**
     * Authorized user
     *
     * @var UserDataContainer
     */
    protected $user;

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

        $user = new \stdClass;
        $user->id = $userId;
        $user->token = $token;

        $this->user = new UserDataContainer($user);

        parent::execute($action, $request, $response);
    }

    /**
     * Method returns offset for pagination
     *
     * @return int
     */
    protected function getOffsetNumber()
    {
        try {
            $offset = (int)$this->request->get('offset', true);
        } catch(\InvalidArgumentException $exception) {
            $offset = 0;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        return $offset;
    }

    /**
     * Method returns array with mandatory information for the pagination
     *
     * @param int $offset
     * @param int $numberOfResults
     * @param int $limit - default BaseEntity::DEFAULT_ROWS_LIMIT
     *
     * @return array
     */
    protected function getPaginationBlock($offset, $numberOfResults, $limit = BaseEntity::DEFAULT_ROWS_LIMIT)
    {
        return [
            'num_results' => $numberOfResults,
            'limit' => $limit,
            'offset' => $offset
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
        $requestData = $this->request->getData();
        $method = $this->request->getMethod();

        if (!isset($requestData[$method])) {
            throw new BadRequestException;
        }

        foreach ($keys as $key) {
            if (!isset($requestData[$method][$key])) {
                throw new BadRequestException('Variable "' . $key . '" must be specified!');
            }
        }
    }
} 