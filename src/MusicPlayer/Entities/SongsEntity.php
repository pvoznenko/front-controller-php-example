<?php
namespace MusicPlayer\Entities;

use App\DataLayer\BaseEntity;
use App\DataLayer\Param;

/**
 * Class SongsEntity
 * @package MusicPlayer\Entities
 *
 * Model represents action on Songs in DB
 */
class SongsEntity extends BaseEntity
{
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'songs';

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
        $selectData = ['id', 'user_id', 'playlist_id', 'track', 'artist', 'album'];

        $data = [
            'user_id' => new Param($userId, SQLITE3_INTEGER),
            'playlist_id' => new Param($playlistId, SQLITE3_INTEGER)
        ];

        if ($songId !== null) {
            $data['id'] = new Param($songId, SQLITE3_INTEGER);
            $offset = null;
        }

        $result = $this->selectData($selectData, $data, \PDO::FETCH_ASSOC, $offset);

        return $result === false ? [] : $result;
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
        $data = [
            'playlist_id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return (int)$this->selectData(['COUNT(id)'], $data, \PDO::FETCH_COLUMN);
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
        $data = [
            'track' => new Param($track, SQLITE3_TEXT),
            'artist' => new Param($artist, SQLITE3_TEXT),
            'album' => new Param($album, SQLITE3_TEXT),
            'playlist_id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return (bool)$this->selectData(['id'], $data);
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
        $data = [
            'track' => new Param($track, SQLITE3_TEXT),
            'artist' => new Param($artist, SQLITE3_TEXT),
            'album' => new Param($album, SQLITE3_TEXT),
            'playlist_id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->insertData($data);
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
        $data = [
            'id' => new Param($songId, SQLITE3_INTEGER),
            'playlist_id' => new Param($playlistId, SQLITE3_INTEGER),
            'user_id' => new Param($userId, SQLITE3_INTEGER)
        ];

        return $this->deleteData($data);
    }
} 