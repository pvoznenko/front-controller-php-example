<?php
namespace MusicPlayer\Entities;

use App\DataLayer\BaseEntity;
use App\DataLayer\Param;

/**
 * Class PlaylistEntity
 * @package MusicPlayer\Entities
 *
 * Model represents action on Playlist in DB
 */
class PlaylistEntity extends BaseEntity
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
     * @param int $offset - current offset for data pagination, default 0
     *
     * @return array - if successful will return playlist, otherwise empty list
     */
    public function getPlaylist($userId, $playlistId = null, $offset = 0)
    {
        $selectData = ['id', 'user_id', 'name'];

        $data = [
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        if ($playlistId !== null) {
            $data['id'] = new Param($playlistId, SQLITE3_INTEGER);
            $offset = null;
        }

        $result = $this->selectData($selectData, $data, \PDO::FETCH_ASSOC, $offset);

        return $result === false ? [] : $result;
    }

    /**
     * Method returns amount of playlist that specified user have
     *
     * @param int $userId - user id
     * @return int
     */
    public function getPlaylistCount($userId)
    {
        $data = [
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return (int)$this->selectData(['COUNT(id)'], $data, \PDO::FETCH_COLUMN);
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
        $data = [
            'name' => new Param($name, SQLITE3_TEXT),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->selectData(['id', 'user_id', 'name'], $data);
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
        $data = [
            'name' => new Param($name, SQLITE3_TEXT),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->insertData($data);
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
        $what = [
            'name' => new Param($newName, SQLITE3_TEXT)
        ];

        $where = [
            'id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->updateData($what, $where);
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
        $data = [
            'id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->deleteData($data);
    }
} 