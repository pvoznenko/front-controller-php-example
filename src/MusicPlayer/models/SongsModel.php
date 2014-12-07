<?php
namespace MusicPlayer\models;

use app\BaseModel;

/**
 * Class SongsModel
 * @package MusicPlayer\models
 *
 * Model represents action on Songs in DB
 */
class SongsModel extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $tableName = 'songs';

    /**
     * Method return song from specified playlist for authorized user
     *
     * @param int $songId - song id
     * @param int $playlistId - playlist id
     * @param int $userId - user id
     *
     * @return array|bool - song data or if not exist false
     */
    public function getSongFromPlaylist($songId, $playlistId, $userId)
    {
        $statement = $this->db->prepare('
            SELECT id, user_id, playlist_id, track, artist, album
            FROM `' . $this->tableName . '`
            WHERE id = :songId AND playlist_id = :playlistId AND user_id = :userId
        ');
        $statement->bindValue(':songId', $songId, SQLITE3_INTEGER);
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($statement->execute()) {
            return $statement->fetch(\PDO::FETCH_ASSOC);
        }

        return false;
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
        $statement = $this->db->prepare('
            SELECT id, user_id, playlist_id, track, artist, album
            FROM `' . $this->tableName . '`
            WHERE track = :track AND artist = :artist AND album = :album AND playlist_id = :playlistId AND user_id = :userId
        ');

        $statement->bindValue(':track', $track, SQLITE3_TEXT);
        $statement->bindValue(':artist', $artist, SQLITE3_TEXT);
        $statement->bindValue(':album', $album, SQLITE3_TEXT);
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($statement->execute()) {
            return (bool)$statement->fetch(\PDO::FETCH_ASSOC);
        }

        return false;
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
        $statement = $this->db->prepare(
            'INSERT INTO `' . $this->tableName . '` (track, artist, album, playlist_id, user_id) VALUES (:track, :artist, :album, :playlistId, :userId)'
        );
        $statement->bindValue(':track', $track, SQLITE3_TEXT);
        $statement->bindValue(':artist', $artist, SQLITE3_TEXT);
        $statement->bindValue(':album', $album, SQLITE3_TEXT);
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        if ($statement->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
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
        $statement = $this->db->prepare(
            'DELETE FROM `' . $this->tableName . '` WHERE id = :songId AND playlist_id = :playlistId AND user_id = :userId');
        $statement->bindValue(':songId', $songId, SQLITE3_INTEGER);
        $statement->bindValue(':playlistId', $playlistId, SQLITE3_INTEGER);
        $statement->bindValue(':userId', $userId, SQLITE3_INTEGER);

        return $statement->execute();
    }
} 