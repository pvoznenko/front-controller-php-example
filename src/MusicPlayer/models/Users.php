<?php
namespace MusicPlayer\models;

use app\BaseModel;

class Users extends BaseModel
{
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
        $statement = $this->db->prepare('INSERT INTO `' . $this->tableName . '` (`token`) VALUES (:token)');
        $statement->bindValue(':token', $token, SQLITE3_TEXT);

        if ($statement->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Method will return user id by specified auth token
     *
     * @param string $token - auth token
     * @return bool|int - if successful you will get user id, otherwise false
     */
    public function getUserIdByToken($token)
    {
        $statement = $this->db->prepare('SELECT id FROM `' . $this->tableName . '` WHERE token = :token');
        $statement->bindValue(':token', $token, SQLITE3_TEXT);

        if ($statement->execute()) {
            return (int)$statement->fetch(\PDO::FETCH_COLUMN);
        }

        return false;
    }
} 