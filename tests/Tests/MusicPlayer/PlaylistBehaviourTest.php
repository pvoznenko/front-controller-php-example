<?php
namespace Tests\MusicPlayer;

use Tests\BaseWebTestClass;
use \Guzzle\Http\Exception\BadResponseException;

/**
 * Class PlaylistBehaviourTest
 * @package Tests\MusicPlayer
 *
 * Here we going to test playlist API
 */
class PlaylistBehaviourTest extends BaseWebTestClass
{

    /**
     * Test authentication failure
     *
     * @covers \MusicPlayer\MusicPlayerAuthController::execute
     */
    public function testAuthFailure()
    {
        /**
         * Accessing private API without authentication token should ended up with an error
         */
        try {
            $this->client->get(BASE_API_URL . '/playlist', ['Accept' => 'application/json'])->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 401, 'Status of response should be 401!');
        }

        /**
         * Accessing private API with wrong authentication token should ended up with an error
         */
        try {
            $this->client->get(BASE_API_URL . '/playlist',
                ['Accept' => 'application/json', 'token' => 'qwerty'])->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 401, 'Status of response should be 401!');

        }
    }

    /**
     * Test authentication success
     *
     * @covers \MusicPlayer\Controllers\PlaylistController::getPlaylist
     */
    public function testAuthSuccess()
    {
        $request = $this->client->get(BASE_API_URL . '/playlist', $this->getAuthHeaders());
        $response = $request->send();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
    }

    /**
     * Test possibility of adding new playlist
     *
     * @depends testAuthSuccess
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::addPlaylist
     */
    public function testAddingNewPlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Lets try to post without data, we should get error
         */
        try {
            $this->client
                ->post(BASE_API_URL . '/playlist', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }

        /**
         * Now we should create new playlist
         */
        $playlistName = 'New Test Playlist';
        $request = $this->client->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName]);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 201, 'Status of response should be 201!');
        $this->assertTrue(isset($decodedResponse['playlist']) && isset($decodedResponse['playlist']['id'])
            , 'Data regarding playlist should be presented!');

        /**
         * For duplication of creation request we should get error
         */
        try {
            $this->client
                ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }
    }

    /**
     * Test covers getting playlist functionality
     *
     *
     *
     * @covers \MusicPlayer\Controllers\PlaylistController::getPlaylist
     */
    public function testGetPlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        $playlistName = 'New Test Playlist';
        $decodedResponse = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send()
            ->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Since we created playlist, we should get list of playlist
         */
        $request = $this->client->get(BASE_API_URL . '/playlist', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['playlist']) && count($decodedResponse['playlist']) > 0
            , 'Data regarding playlist should be presented!');

        /**
         * We should get data about our new playlist
         */
        $request = $this->client->get(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['playlist']) && isset($decodedResponse['playlist']['name'])
            , 'Data regarding playlist should be presented!');
        $this->assertTrue($decodedResponse['playlist']['name'] == $playlistName, 'Expected names should be equal!');

        /**
         * Lets try to get not existing playlist, we should get an error
         */
        try {
            $this->client
                ->get(BASE_API_URL . '/playlist/1234', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets check pagination
         *
         * Following block should return our new playlist
         */
        $request = $this->client->get(BASE_API_URL . '/playlist?offset=0', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['playlist']) && count($decodedResponse['playlist']) > 0
            , 'Data regarding playlist should be presented!');
        $this->assertTrue(isset($decodedResponse['info']) && count($decodedResponse['info']) > 0
            , 'Data regarding pagination should be presented!');

        /**
         * Following block should return empty list
         */
        $request = $this->client->get(BASE_API_URL . '/playlist?offset=20', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['playlist']) && count($decodedResponse['playlist']) == 0
            , 'Should be empty response');

        /**
         * Following block should return playlist list
         */
        $request = $this->client->get(BASE_API_URL . '/playlist?offset=0', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['playlist']) && count($decodedResponse['playlist']) > 0
            , 'Data regarding playlist should be presented!');

        /**
         * Lets try to wrong param type in pagination, we should get an error
         */
        try {
            $this->client
                ->get(BASE_API_URL . '/playlist?offset=-1', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets try to wrong param type in pagination, we should get an error
         */
        try {
            $this->client
                ->get(BASE_API_URL . '/playlist?offset=asd', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }
    }

    /**
     * Test playlist update
     *
     * @depends testAddingNewPlaylist
     * @depends testGetPlaylist
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::updatePlaylist
     */
    public function testUpdatePlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Now we should create new playlist
         */
        $playlistName = 'New Test Playlist';
        $response = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send();
        $decodedResponse = $response->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets try to update without proper data, we should get error
         */
        try {
            $this->client
                ->put(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }

        /**
         * Lets try to update existing playlist for wrong user
         */
        try {
            $decodedResponse = $this->client
                ->post(BASE_API_URL . '/users/authentication', ['Accept' => 'application/json'])
                ->send()
                ->json();
            $this->client
                ->put(BASE_API_URL . '/playlist/' . $playlistId,
                    ['Accept' => 'application/json', 'token' => $decodedResponse['token']]
                    , ['newName' => '1'])
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets update new playlist
         */
        $newPlaylistName = 'Updated Test Playlist';
        $request = $this->client->put(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders,
            ['newName' => $newPlaylistName]);
        $response = $request->send();
        $this->assertEquals($response->getStatusCode(), 204, 'Status of response should be 204!');

        /**
         * We should get updated playlist
         */
        $decodedResponse = $this->client
            ->get(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders)
            ->send()
            ->json();
        $this->assertTrue($decodedResponse['playlist']['name'] == $newPlaylistName,
            'Expected new names should be equal!');

        /**
         * Lets create second playlist
         */
        $playlistName = 'New Test Playlist';
        $response = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send();
        $decodedResponse = $response->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets try to update existing playlist with the same name as we already have, should get error
         */
        try {
            $newPlaylistName = 'Updated Test Playlist';
            $this->client->put(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders,
                ['newName' => $newPlaylistName])->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }
    }

    /**
     * Test playlist deletion
     *
     * @depends testAddingNewPlaylist
     * @depends testGetPlaylist
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::deletePlaylist
     */
    public function testPlaylistDeletion()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Now we should create new playlist
         */
        $playlistName = 'New Test Playlist';
        $response = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send();
        $decodedResponse = $response->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets try to delete existing playlist for wrong user
         */
        try {
            $decodedResponse = $this->client
                ->post(BASE_API_URL . '/users/authentication', ['Accept' => 'application/json'])
                ->send()
                ->json();
            $this->client
                ->delete(BASE_API_URL . '/playlist/' . $playlistId,
                    ['Accept' => 'application/json', 'token' => $decodedResponse['token']])
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets delete playlist
         */
        $request = $this->client->delete(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders);
        $response = $request->send();
        $this->assertEquals($response->getStatusCode(), 204, 'Status of response should be 204!');

        /**
         * For duplication of deletion request we should get error
         */
        try {
            $this->client
                ->delete(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets try to get not existing playlist, we should get an error
         */
        try {
            $this->client
                ->get(BASE_API_URL . '/playlist/' . $playlistId, $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * We should get empty list of playlist
         */
        $decodedResponse = $this->client
            ->get(BASE_API_URL . '/playlist', $authHeaders)
            ->send()
            ->json();
        $this->assertTrue(empty($decodedResponse['playlist']));
    }

    /**
     * Test possibility of adding songs to specified playlist
     *
     * @depends testAddingNewPlaylist
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::addSongToPlaylist
     */
    public function testAddingSongToPlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Lets create new playlist
         */
        $playlistName = 'New Test Playlist with Songs';
        $decodedResponse = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send()
            ->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets try to add song to playlist without put data, should get error
         */
        try {
            $this->client->put(BASE_API_URL . '/playlist/' . $playlistId . '/songs', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }

        /**
         * Lets add new song to playlist
         */
        $track = 'New Track';
        $artist = 'New Artist';
        $album = 'New Album';

        $data = ['track' => $track, 'artist' => $artist, 'album' => $album];

        $request = $this->client->put(BASE_API_URL . '/playlist/' . $playlistId . '/songs', $authHeaders, $data);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 201, 'Status of response should be 201!');
        $this->assertTrue(isset($decodedResponse['song']) && isset($decodedResponse['song']['id'])
            , 'Data regarding song should be presented!');
        /**
         * Lets try to do double add of existing song to playlist, should get error
         */
        try {
            $this->client->put(BASE_API_URL . '/playlist/' . $playlistId . '/songs', $authHeaders, $data)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 400, 'Status of response should be 400!');
        }
    }

    /**
     * Test covers getting songs from specific playlist functionality
     *
     * @depends testAddingNewPlaylist
     * @depends testAddingSongToPlaylist
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::addSongToPlaylist
     */
    public function testGetSongsFromPlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Lets create new playlist
         */
        $playlistName = 'New Test Playlist with Songs';
        $decodedResponse = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send()
            ->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets add new song to playlist
         */
        $track = 'New Track';
        $artist = 'New Artist';
        $album = 'New Album';

        $data = ['track' => $track, 'artist' => $artist, 'album' => $album];

        $decodedResponse = $this->client->put(BASE_API_URL . '/playlist/' . $playlistId . '/songs', $authHeaders,
            $data)->send()->json();

        $songId = $decodedResponse['song']['id'];

        $playlistSongsUri = BASE_API_URL . '/playlist/' . $playlistId . '/songs';

        /**
         * Lets try to get songs from not existing playlist, should fail
         */
        try {
            $this->client->get(BASE_API_URL . '/playlist/123213/songs', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Now we should get list of songs
         */
        $request = $this->client->get($playlistSongsUri, $authHeaders, $data);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['songs']) && count($decodedResponse['songs']) > 0
            , 'Data regarding song should be presented!');

        /**
         * Lets try to get not existing songs from playlist, should fail
         */
        try {
            $this->client->get($playlistSongsUri . '/123312', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Here we should get data regarding particular song
         */
        $request = $this->client->get($playlistSongsUri . '/' . $songId, $authHeaders, $data);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['song']) && isset($decodedResponse['song']['id'])
            , 'Data regarding song should be presented!');
        $song = $decodedResponse['song'];
        $this->assertTrue($song['track'] == $track && $song['album'] == $album && $song['artist'] == $artist
            , 'Data regarding song should be equal!');

        /**
         * Lets check pagination
         *
         * Following block should return our song list
         */
        $request = $this->client->get($playlistSongsUri . '?offset=0', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['songs']) && count($decodedResponse['songs']) > 0
            , 'Data regarding playlist should be presented!');
        $this->assertTrue(isset($decodedResponse['info']) && count($decodedResponse['info']) > 0
            , 'Data regarding pagination should be presented!');

        /**
         * Following block should return empty list
         */
        $request = $this->client->get($playlistSongsUri . '?offset=20', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();

        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['songs']) && count($decodedResponse['songs']) == 0
            , 'Should be empty response');


        /**
         * Following block should return song list
         */
        $request = $this->client->get($playlistSongsUri . '?offset=0', $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['songs']) && count($decodedResponse['songs']) > 0
            , 'Data regarding playlist should be presented!');

        /**
         * Lets try to wrong param type in pagination, we should get an error
         */
        try {
            $this->client
                ->get($playlistSongsUri . '?offset=-2', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets try to wrong param type in pagination, we should get an error
         */
        try {
            $this->client
                ->get($playlistSongsUri . '?offset=asd', $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }
    }

    /**
     * Test playlist deletion
     *
     * @depends testAddingNewPlaylist
     * @depends testAddingSongToPlaylist
     * @depends testGetSongsFromPlaylist
     *
     * @covers  \MusicPlayer\Controllers\PlaylistController::deleteSongFromPlaylist
     */
    public function testDeleteSongFromPlaylist()
    {
        $authHeaders = $this->getAuthHeaders();

        /**
         * Now we should create new playlist
         */
        $playlistName = 'New Test Playlist with Songs';
        $response = $this->client
            ->post(BASE_API_URL . '/playlist', $authHeaders, ['name' => $playlistName])
            ->send();
        $decodedResponse = $response->json();

        $playlistId = $decodedResponse['playlist']['id'];

        /**
         * Lets add new song to playlist
         */
        $track = 'New Track';
        $artist = 'New Artist';
        $album = 'New Album';

        $data = ['track' => $track, 'artist' => $artist, 'album' => $album];

        $decodedResponse = $this->client
            ->put(BASE_API_URL . '/playlist/' . $playlistId . '/songs', $authHeaders, $data)
            ->send()
            ->json();
        $songId = $decodedResponse['song']['id'];

        /**
         * Lets try to delete song from existing playlist for wrong user
         */
        try {
            $decodedResponse = $this->client
                ->post(BASE_API_URL . '/users/authentication', ['Accept' => 'application/json'])
                ->send()
                ->json();
            $this->client
                ->delete(BASE_API_URL . '/playlist/' . $playlistId . '/songs/' . $songId,
                    ['Accept' => 'application/json', 'token' => $decodedResponse['token']])
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Lets delete song from playlist
         */
        $request = $this->client->delete(BASE_API_URL . '/playlist/' . $playlistId . '/songs/' . $songId, $authHeaders);
        $response = $request->send();
        $this->assertEquals($response->getStatusCode(), 204, 'Status of response should be 204!');

        /**
         * For duplication of deletion request we should get error
         */
        try {
            $this->client
                ->delete(BASE_API_URL . '/playlist/' . $playlistId . '/songs/' . $songId, $authHeaders)
                ->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        /**
         * Song should not be exist
         */
        try {
            $this->client->get(BASE_API_URL . '/playlist/' . $playlistId . '/songs/' . $songId, $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }
    }
}