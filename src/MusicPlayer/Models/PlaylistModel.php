<?php
namespace MusicPlayer\Models;

use App\DataLayer\BaseModel;
use MusicPlayer\Entities\PlaylistEntity;

/**
 * Class PlaylistModel
 * @package MusicPlayer\Models
 *
 * Model represents action on Playlist
 */
class PlaylistModel extends BaseModel
{
    /**
     * @var PlaylistEntity
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = new PlaylistEntity;
        parent::__construct();
    }

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
        $cacheKey = sprintf('playlist:%d:getPlaylist:%d:%d', $userId, $playlistId, $offset);
        $callback = function ($this) use ($userId, $playlistId, $offset) {
            return $this->entity->getPlaylist($userId, $playlistId, $offset);
        };

        return $this->getData($cacheKey, $callback);
    }

    /**
     * Method returns amount of playlist that specified user have
     *
     * @param int $userId - user id
     * @return int
     */
    public function getPlaylistCount($userId)
    {
        $cacheKey = sprintf('playlist:%d:getPlaylistCount', $userId);
        $callback = function ($this) use ($userId) {
            return $this->entity->getPlaylistCount($userId);
        };

        return $this->getData($cacheKey, $callback);
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
        $cacheKey = sprintf('playlist:%d:getPlaylistByName:%s', $userId, base64_encode($name));
        $callback = function ($this) use ($name, $userId) {
            return $this->entity->getPlaylistByName($name, $userId);
        };

        return $this->getData($cacheKey, $callback);
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
        $cacheKeyPattern = sprintf('playlist:%d:*', $userId);
        $callback = function ($this) use ($name, $userId) {
            return $this->entity->addPlaylist($name, $userId);
        };

        return $this->clearCache($cacheKeyPattern, $callback);
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
        $cacheKeyPattern = sprintf('playlist:%d:*', $userId);
        $callback = function ($this) use ($playlistId, $userId, $newName) {
            return $this->entity->updatePlaylist($playlistId, $userId, $newName);
        };

        return $this->clearCache($cacheKeyPattern, $callback);
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
        $cacheKeyPattern = sprintf('playlist:%d:*', $userId);
        $callback = function ($this) use ($playlistId, $userId) {
            return $this->entity->deletePlaylist($playlistId, $userId);
        };

        return $this->clearCache($cacheKeyPattern, $callback);
    }
} 