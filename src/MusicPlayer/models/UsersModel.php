<?php
namespace MusicPlayer\Models;

use App\DataLayer\BaseModel;
use MusicPlayer\Entities\UsersEntity;

/**
 * Class UsersModel
 * @package MusicPlayer\Models
 *
 * Model represents action on Users in DB
 */
class UsersModel extends BaseModel
{
    /**
     * @var UsersEntity
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = new UsersEntity;
    }

    /**
     * Method will add user with token (in scope of test application user is only id and auth token)
     *
     * @param string $token - auth token
     *
     * @return bool|int - will returned user id if successful otherwise false
     */
    public function addUser($token)
    {
        return $this->entity->addUser($token);
    }

    /**
     * Method will return user id by specified auth token
     *
     * @param string $token - auth token
     * @return bool|int - if successful you will get user id, otherwise false
     */
    public function getUserIdByToken($token)
    {
        return $this->entity->getUserIdByToken($token);
    }
} 