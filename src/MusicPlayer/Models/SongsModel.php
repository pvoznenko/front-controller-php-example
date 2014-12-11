<?php
namespace MusicPlayer\Models;

use App\DataLayer\BaseModel;
use MusicPlayer\Entities\SongsEntity;

/**
 * Class SongsModel
 * @package MusicPlayer\Models
 *
 * Model represents action on Songs in DB
 */
class SongsModel extends BaseModel
{
    /**
     * @var SongsEntity
     */
    protected $entity;

    public function __construct()
    {
        $this->entity = new SongsEntity;
        parent::__construct();
    }

    /**
     * Method return songs from specified playlist for authorized user
     * If song id is specified it will return data regarding this song
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     * @param int|null $songId - song id, default null
     * @param int $offset - current offset for data pagination, default 0
     *
     * @return array - song(s) data or if not exist empty array
     */
    public function getSongsFromPlaylist($playlistId, $userId, $songId = null, $offset = 0)
    {
        $cacheKey = sprintf('songs:%d:%d:getSongsFromPlaylist:%d:%d', $userId, $playlistId, $songId, $offset);
        $callback = function($this) use($playlistId, $userId, $songId, $offset) { return $this->entity->getSongsFromPlaylist($playlistId, $userId, $songId, $offset); };

        return $this->getData($cacheKey, $callback);
    }

    /**
     * Method returns amount of songs for specified playlist and user
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     * @return int
     */
    public function getSongsCount($playlistId, $userId)
    {
        $cacheKey = sprintf('songs:%d:%d:getSongsCount', $userId, $playlistId);
        $callback = function($this) use($playlistId, $userId) { return $this->entity->getSongsCount($playlistId, $userId); };

        return $this->getData($cacheKey, $callback);
    }

    /**
     * Method return playlist by name for authorized user
     *
     * @param string $track - track
     * @param string $artist - artist
     * @param string $album - album
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return bool
     */
    public function isSongInPlaylist($track, $artist, $album, $playlistId, $userId)
    {
        $cacheKey = sprintf('songs:%d:%d:isSongInPlaylist:%s', $userId, $playlistId, base64_encode($track . $artist . $album));
        $callback = function($this) use($track, $artist, $album, $playlistId, $userId) { return $this->entity->isSongInPlaylist($track, $artist, $album, $playlistId, $userId); };

        return $this->getData($cacheKey, $callback);
    }

    /**
     * Method adds song to playlist of specified user
     *
     * @param string $track - track
     * @param string $artist - artist
     * @param string $album - album
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return bool|int - if successful will return new song id, otherwise false
     */
    public function addSongToPlaylist($track, $artist, $album, $playlistId, $userId)
    {
        $cacheKeyPattern = sprintf('songs:%d:%d:*', $userId, $playlistId);
        $callback = function($this) use($track, $artist, $album, $playlistId, $userId) { return $this->entity->addSongToPlaylist($track, $artist, $album, $playlistId, $userId); };

        return $this->clearCache($cacheKeyPattern, $callback);
    }

    /**
     * Method deletes song from playlist of specified user
     *
     * @param int $songId - song id
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return bool - true if successful
     */
    public function deleteSongFromPlaylist($songId, $playlistId, $userId)
    {
        $cacheKeyPattern = sprintf('songs:%d:%d:*', $userId, $playlistId);
        $callback = function($this) use($songId, $playlistId, $userId) { return $this->entity->deleteSongFromPlaylist($songId, $playlistId, $userId); };

        return $this->clearCache($cacheKeyPattern, $callback);
    }

    /**
     * Method deletes all songs from playlist of specified user
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return bool - true if successful
     */
    public function deleteAllSongsFromPlaylist($playlistId, $userId)
    {
        $cacheKeyPattern = sprintf('songs:%d:%d:*', $userId, $playlistId);
        $callback = function($this) use($playlistId, $userId) { return $this->entity->deleteAllSongsFromPlaylist($playlistId, $userId); };

        return $this->clearCache($cacheKeyPattern, $callback);
    }
} 