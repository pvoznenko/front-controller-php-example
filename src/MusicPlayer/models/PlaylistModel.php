<?php
namespace MusicPlayer\models;

use app\BaseModel;

/**
 * Class PlaylistModel
 * @package MusicPlayer\models
 *
 * Model represents action on Playlist in DB
 */
class PlaylistModel extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'playlist';

    /**
     * Method return list of all playlist that specified user have
     * If playlist id specified, then it will return only following playlist
     *
     * @param int $userId - user id
     * @param int|null $playlistId - playlist id, default null
     *
     * @return array - if successful will return playlist, otherwise empty list
     */
    public function getPlaylist($userId, $playlistId = null)
    {
        $query = '
            SELECT id, user_id, name
            FROM `' . $this->tableName . '`
            WHERE user_id = :userId
        ';

        if ($playlistId !== null) {
            $query .= ' AND id = :playlistId';
        }

        $statement = $this->db->prepare($query);

        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($playlistId !== null) {
            $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        }

        $result = [];

        if ($statement->execute()) {
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return $result === false ? [] : $result;
    }

    /**
     * Method return playlist by name for authorized user
     *
     * @param string $name - playlist name
     * @param int $userId - user id
     *
     * @return array|bool - if successful will return playlist, otherwise false
     */
    public function getPlaylistByName($name, $userId)
    {
        $statement = $this->db->prepare('
            SELECT id, user_id, name
            FROM `' . $this->tableName . '`
            WHERE name = :name AND user_id = :userId
        ');

        $statement->bindValue(':name', $name, SQLITE3_TEXT);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($statement->execute()) {
            return $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return false;
    }

    /**
     * Method adds new playlist to specified user
     *
     * @param string $name - playlist name
     * @param int $userId - user id
     *
     * @return bool|int - if successful will return new playlist id, otherwise false
     */
    public function addPlaylist($name, $userId)
    {
        $statement = $this->db->prepare('INSERT INTO `' . $this->tableName . '` (`name`,`user_id`) VALUES (:name, :userId)');
        $statement->bindValue(':name', $name, SQLITE3_TEXT);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($statement->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Method updates playlist name for specified user
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     * @param string $newName - playlist new name
     *
     * @return bool - true if successful
     */
    public function updatePlaylist($playlistId, $userId, $newName)
    {
        $statement = $this->db->prepare('
            UPDATE `' . $this->tableName . '` SET name = :newName WHERE id = :playlistId AND user_id = :userId
        ');
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);
        $statement->bindValue(':newName', $newName, SQLITE3_TEXT);

        return $statement->execute();
    }

    /**
     * Method deletes playlist name for specified user
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return bool - true if successful
     */
    public function deletePlaylist($playlistId, $userId)
    {
        $statement = $this->db->prepare('DELETE FROM `' . $this->tableName . '` WHERE id = :playlistId AND user_id = :userId');
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        return $statement->execute();
    }
} 