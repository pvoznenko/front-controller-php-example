<?php
namespace MusicPlayer\Controllers;

use MusicPlayer\MusicPlayerAuthController;
use MusicPlayer\Models\PlaylistModel;
use MusicPlayer\Models\SongsModel;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;

/**
 * Class PlaylistController
 * @package MusicPlayer\Controllers
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
        $offset = $this->getOffsetNumber();
        $playlistModel = new PlaylistModel;
        $currentUserId = $this->user->getId();

        $playlist = $playlistModel->getPlaylist($currentUserId, $playlistId, $offset);

        $responseData = ['playlist' => $playlist];

        if ($playlistId !== null) {
            // if $playlistId specified then we need to return specific playlist for user otherwise show 404
            if (empty($playlist)) {
                throw new NotFoundException('Playlist not found!');
            }
        } else {
            // if $playlistId not specified then we need to return list of available playlist for user
            $numberOfResults = $playlistModel->getPlaylistCount($currentUserId);
            $responseData['info'] = $this->getPaginationBlock($offset, $numberOfResults);
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

        $playlistName = $this->request->get('name');

        $playlistModel = new PlaylistModel;

        $currentUserId = $this->user->getId();

        if ($playlistModel->getPlaylistByName($playlistName, $currentUserId) !== false) {
            throw new BadRequestException('Playlist with this name already exists!');
        }

        $playlistId = $playlistModel->addPlaylist($playlistName, $currentUserId);

        $responseData = ['playlist' => ['id' => $playlistId, 'userId' => $currentUserId, 'name' => $playlistName]];

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

        $playlistName = $this->request->get('newName');
        $playlistModel = new PlaylistModel;
        $currentUserId = $this->user->getId();

        if ($playlistModel->getPlaylistByName($playlistName, $currentUserId) !== false) {
            throw new BadRequestException('Playlist with this name already exists!');
        }

        $updated = $playlistModel->updatePlaylist($playlistId, $currentUserId, $playlistName);

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
        $deleted = (new PlaylistModel)->deletePlaylist($playlistId, $this->user->getId());

        if (!$deleted) {
            // Duplication of response will rise `404`, I know it is Holly War about idempotent in HTTP and DELETE
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

        $track = $this->request->get('track');
        $artist = $this->request->get('artist');
        $album = $this->request->get('album');

        $songsModel = new SongsModel;
        $currentUserId = $this->user->getId();

        if ($songsModel->isSongInPlaylist($track, $artist, $album, $playlistId, $currentUserId)) {
            throw new BadRequestException('Song already in specified playlist!');
        }

        $songId = $songsModel->addSongToPlaylist($track, $artist, $album, $playlistId, $currentUserId);

        $responseData = ['song' => [
            'id' => $songId,
            'track' => $track,
            'artist' => $artist,
            'album' => $album,
            'playlistId' => $playlistId,
            'userId' => $currentUserId
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
        $currentUserId = $this->user->getId();
        $playlist = (new PlaylistModel)->getPlaylist($currentUserId, $playlistId);

        if (empty($playlist)) {
            throw new NotFoundException('Playlist not found!');
        }

        $offset = $this->getOffsetNumber();
        $songsModel = new SongsModel;
        $data = $songsModel->getSongsFromPlaylist($playlistId, $currentUserId, $songId, $offset);

        $responseKey = $songId !== null ? 'song' : 'songs';
        $responseData = [$responseKey => $data];

        if ($songId !== null) {
            // if $songId specified then we need to return specific song for user otherwise show 404
            if (empty($data)) {
                throw new NotFoundException('Song not found!');
            }
        } else {
            // if $songId not specified then we need to return list of available songs in playlist for user
            $numberOfResults = $songsModel->getSongsCount($playlistId, $currentUserId);
            $responseData['info'] = $this->getPaginationBlock($offset, $numberOfResults);
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
        $deleted = (new SongsModel)->deleteSongFromPlaylist($songId, $playlistId, $this->user->getId());

        if (!$deleted) {
            // Duplication of response will rise `404`, I know it is Holly War about idempotent in HTTP and DELETE
            throw new NotFoundException('Song not found!');
        }

        $this->response->addHeader('204 No Content')->send();
    }
} 