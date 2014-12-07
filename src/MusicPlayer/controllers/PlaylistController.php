<?php
namespace MusicPlayer\controllers;

use MusicPlayer\MusicPlayerAuthController;
use MusicPlayer\models\PlaylistModel;
use MusicPlayer\models\SongsModel;
use app\exceptions\BadRequestException;
use app\exceptions\NotFoundException;

/**
 * Class PlaylistController
 * @package MusicPlayer\controllers
 *
 * Controller responsible for CRUD playlist
 */
class PlaylistController extends MusicPlayerAuthController
{
    /**
     * Method will send back list of all playlist that authorized user have
     * If playlist id specified, then it will return only following playlist
     *
     * @param int|null $playlistId - playlist id, default null
     */
    public function getPlaylist($playlistId = null)
    {
        $playlist = (new PlaylistModel)->getPlaylist($this->userId, $playlistId);
        $this->response->addHeader('200 OK')->send(['playlist' => $playlist]);
    }

    /**
     * Method creates new playlist
     *
     * @throws BadRequestException - if name of playlist is missing in post data or playlist with specified name already exists
     */
    public function addPlaylist()
    {
        $requestData = $this->request->getRawData();

        if (!isset($requestData['POST']) || !isset($requestData['POST']['name'])) {
            throw new BadRequestException('Name of playlist must be specified!');
        }

        $playlistName = $requestData['POST']['name'];

        $playlistModel = new PlaylistModel;

        if ($playlistModel->getPlaylistByName($playlistName, $this->userId) !== false) {
            throw new BadRequestException('Playlist with this name already exists!');
        }

        $playlistId = $playlistModel->addPlaylist($playlistName, $this->userId);

        $responseData = ['playlist' => ['id' => $playlistId, 'userId' => $this->userId, 'name' => $playlistName]];

        $this->response->addHeader('201 Created')->send($responseData);
    }

    /**
     * Method updates specified playlist for authorized user
     *
     * @param int $playlistId - playlist id
     *
     * @throws BadRequestException - if new name of playlist is missing in post data
     * @throws NotFoundException - if could not update specified playlist for authorized user
     */
    public function updatePlaylist($playlistId)
    {
        $requestData = $this->request->getRawData();

        if (!isset($requestData['PUT']) || !isset($requestData['PUT']['newName'])) {
            throw new BadRequestException('New name of playlist must be specified!');
        }

        $playlistName = $requestData['PUT']['newName'];

        $playlistModel = new PlaylistModel;

        if ($playlistModel->getPlaylistByName($playlistName, $this->userId) !== false) {
            throw new BadRequestException('Playlist with this name already exists!');
        }

        $updated = $playlistModel->updatePlaylist($playlistId, $this->userId, $playlistName);

        if (!$updated) {
            throw new NotFoundException('Could not update specified playlist for authorized user!');
        }

        $this->response->addHeader('204 No Content')->send();
    }

    /**
     * Method deletes specified playlist for authorized user
     *
     * @param int $playlistId - playlist id
     *
     * @throws NotFoundException - if could not delete specified playlist for authorized user
     */
    public function deletePlaylist($playlistId)
    {
        $deleted = (new PlaylistModel)->deletePlaylist($playlistId, $this->userId);

        if (!$deleted) {
            throw new NotFoundException('Could not delete specified playlist for authorized user!');
        }

        $this->response->addHeader('204 No Content')->send();
    }

    /**
     * Method adds new song to the playlist
     *
     * @param int $playlistId - playlist id
     *
     * @throws BadRequestException - if Track, artist or album is missing in post data or song already in specified playlist
     */
    public function addSongToPlaylist($playlistId)
    {
        $requestData = $this->request->getRawData();

        if (!isset($requestData['PUT']) || !isset($requestData['PUT']['track']) || !isset($requestData['PUT']['artist'])
            || !isset($requestData['PUT']['album'])) {
            throw new BadRequestException('Track, artist and album must be specified!');
        }

        $track = $requestData['PUT']['track'];
        $artist = $requestData['PUT']['artist'];
        $album = $requestData['PUT']['album'];

        $songsModel = new SongsModel;

        if ($songsModel->isSongInPlaylist($track, $artist, $album, $playlistId, $this->userId)) {
            throw new BadRequestException('Song already in specified playlist!');
        }

        $songId = $songsModel->addSongToPlaylist($track, $artist, $album, $playlistId, $this->userId);

        $responseData = ['song' => [
            'id' => $songId,
            'track' => $track,
            'artist' => $artist,
            'album' => $album,
            'playlistId' => $playlistId,
            'userId' => $this->userId
        ]];

        $this->response->addHeader('201 Created')->send($responseData);
    }

    /**
     * Method deletes song from playlist of authorized user
     *
     * @param int $playlistId - playlist id
     * @param int $songId - playlist id
     *
     * @throws NotFoundException - if could not delete specified song from playlist of authorized user
     */
    public function deleteSongFromPlaylist($playlistId, $songId)
    {
        $deleted = (new SongsModel)->deleteSongFromPlaylist($songId, $playlistId, $this->userId);

        if (!$deleted) {
            throw new NotFoundException('Could not delete song from playlist of authorized user!');
        }

        $this->response->addHeader('204 No Content')->send();
    }
} 