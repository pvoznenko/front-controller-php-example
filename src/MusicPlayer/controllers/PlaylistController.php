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
     *
     * @throws NotFoundException - if specified playlist not found
     */
    public function getPlaylist($playlistId = null)
    {
        $page = $this->getPageNumber();

        $playlistModel = new PlaylistModel;
        $playlist = $playlistModel->getPlaylist($this->userId, $playlistId, $page);

        $responseData = ['playlist' => $playlist];

        if ($playlistId !== null) {
            if (empty($playlist)) {
                throw new NotFoundException('Playlist not found!');
            }
        } else {
            $numberOfResults = $playlistModel->getPlaylistCount($this->userId);
            $responseData['info'] = $this->getPaginationBlock($page, $numberOfResults);
        }

        $this->response->addHeader('200 OK')->send($responseData);
    }

    /**
     * Method creates new playlist
     *
     * @throws BadRequestException - if name of playlist is missing in post data or playlist with specified name already exists
     */
    public function addPlaylist()
    {
        $this->validatePresentedData(['name']);

        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();

        $playlistName = $requestData[$method]['name'];

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
        $this->validatePresentedData(['newName']);

        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();

        $playlistName = $requestData[$method]['newName'];

        $playlistModel = new PlaylistModel;

        if ($playlistModel->getPlaylistByName($playlistName, $this->userId) !== false) {
            throw new BadRequestException('Playlist with this name already exists!');
        }

        $updated = $playlistModel->updatePlaylist($playlistId, $this->userId, $playlistName);

        if (!$updated) {
            throw new NotFoundException('Playlist not found!');
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
            throw new NotFoundException('Playlist not found!');
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
        $this->validatePresentedData(['track', 'artist', 'album']);

        $requestData = $this->request->getRawData();
        $method = $this->request->getMethod();

        $track = $requestData[$method]['track'];
        $artist = $requestData[$method]['artist'];
        $album = $requestData[$method]['album'];

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
     * Method will send back list of all songs from playlist of authorized user
     * If song id specified, then it will return only following song
     *
     * @param int $playlistId - playlist id
     * @param int|null $songId - song id, default null
     *
     * @throws NotFoundException - if specified playlist not found
     */
    public function getSongsFromPlaylist($playlistId, $songId = null)
    {
        $playlist = (new PlaylistModel)->getPlaylist($this->userId, $playlistId);

        if (empty($playlist)) {
            throw new NotFoundException('Playlist not found!');
        }

        $page = $this->getPageNumber();

        $songsModel = new SongsModel;

        $data = $songsModel->getSongsFromPlaylist($playlistId, $this->userId, $songId, $page);

        $responseKey = $songId !== null ? 'song' : 'songs';
        $responseData = [$responseKey => $data];

        if ($songId !== null) {
            if (empty($data)) {
                throw new NotFoundException('Song not found!');
            }
        } else {
            $numberOfResults = $songsModel->getSongsCount($playlistId, $this->userId);
            $responseData['info'] = $this->getPaginationBlock($page, $numberOfResults);
        }

        $this->response->addHeader('200 OK')->send($responseData);
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
            throw new NotFoundException('Song not found!');
        }

        $this->response->addHeader('204 No Content')->send();
    }
} 