<?php
namespace MusicPlayer\Entities;

use App\DataLayer\BaseEntity;
use App\DataLayer\Param;

/**
 * Class UsersEntity
 * @package MusicPlayer\Entities
 *
 * Responsible for DB communication
 */
class UsersEntity extends BaseEntity
{
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'users';

    /**
     * Method will add user with token (in scope of test application user is only id and auth token)
     *
     * @param string $token - auth token
     *
     * @return bool|int - will returned user id if successful otherwise false
     */
    public function addUser($token)
    {
        $data = ['token' => new Param($token, SQLITE3_TEXT)];

        return $this->insertData($data);
    }

    /**
     * Method will return user id by specified auth token
     *
     * @param string $token - auth token
     * @return bool|int - if successful you will get user id, otherwise false
     */
    public function getUserIdByToken($token)
    {
        $data = ['token' => new Param($token, SQLITE3_TEXT)];

        return $this->getId($data);
    }
} 