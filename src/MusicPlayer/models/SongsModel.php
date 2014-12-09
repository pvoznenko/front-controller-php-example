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
    }

    /**
     * Method return songs from specified playlist for authorized user
     * If song id is specified it will return data regarding this song
     *
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     * @param int|null $songId - song id, default null
     * @param int $page - current page of pagination data, default 1
     *
     * @return array - song(s) data or if not exist empty array
     */
    public function getSongsFromPlaylist($playlistId, $userId, $songId = null, $page = 1)
    {
        return $this->entity->getSongsFromPlaylist($playlistId, $userId, $songId, $page);
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
        return $this->entity->getSongsCount($playlistId, $userId);
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
        return $this->entity->isSongInPlaylist($track, $artist, $album, $playlistId, $userId);
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
        return $this->entity->addSongToPlaylist($track, $artist, $album, $playlistId, $userId);
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
        return $this->entity->deleteSongFromPlaylist($songId, $playlistId, $userId);
    }
} 