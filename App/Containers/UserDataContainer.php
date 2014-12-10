<?php
namespace App\Containers;

use App\BaseContainer;
use App\Interfaces\ContainerInterface;

/**
 * Class UserDataContainer
 * @package App\Containers
 *
 * Use data container
 *
 * @method int getId() - returns user id
 * @method string getToken() - returns user token
 */
class UserDataContainer extends BaseContainer implements ContainerInterface
{
    /**
     * User id
     *
     * @var int
     */
    protected $id;

    /**
     * User auth token
     *
     * @var string
     */
    protected $token;
}