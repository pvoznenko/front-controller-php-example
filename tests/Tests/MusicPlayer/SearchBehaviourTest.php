<?php
namespace Tests\MusicPlayer;

use Tests\BaseWebTestClass;
use \Guzzle\Http\Exception\BadResponseException;

/**
 * Class SearchBehaviourTest
 * @package Tests\MusicPlayer
 *
 * Here we going to test Search API
 */
class SearchBehaviourTest extends BaseWebTestClass
{
    /**
     * Test possible 404 errors on not correct url for search
     */
    public function testSearchFailures()
    {
        $authHeaders = $this->getAuthHeaders();

        try {
            $this->client->get(BASE_API_URL . '/search', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        try {
            $this->client->get(BASE_API_URL . '/search/absa', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        try {
            $this->client->get(BASE_API_URL . '/search/track', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        try {
            $this->client->get(BASE_API_URL . '/search/track?page', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }

        try {
            $this->client->get(BASE_API_URL . '/search/track?q', $authHeaders)->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404, 'Status of response should be 404!');
        }
    }

    /**
     * Test covers cases regarding searching on albums
     */
    public function testSearchAlbum()
    {
        $this->overallSearchTest('The Best Of', 'album');
    }

    /**
     * Test covers cases regarding searching on artists
     */
    public function testSearchArtist()
    {
        $this->overallSearchTest('"queen"', 'artist');
    }

    /**
     * Test covers cases regarding searching on tracks
     */
    public function testSearchTrack()
    {
        /**
         * Simple search
         */
        $this->overallSearchTest('love YOU', 'track');

        /**
         *  Search with parameter
         */
        $this->overallSearchTest('artist:"queen" track:love YOU', 'track');
    }

    /**
     * Overall search test
     *
     * @param string $searchTerm - search term
     * @param string $type - type of search
     */
    private function overallSearchTest($searchTerm, $type)
    {
        $authHeaders = $this->getAuthHeaders();

        $queryParams = http_build_query(['q' => $searchTerm]);

        $request = $this->client->get(BASE_API_URL . '/search/' . $type . '?' . $queryParams, $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['info']) && isset($decodedResponse['info']['num_results'])
            , 'Data regarding info should be presented!');
        $this->assertTrue(isset($decodedResponse['result']) && count($decodedResponse['result']) == $decodedResponse['info']['limit']
            , 'Data regarding result should be presented!');

        $page = (int)($decodedResponse['info']['num_results'] / $decodedResponse['info']['limit']) + 10;

        $queryParams = http_build_query(['q' => $searchTerm, 'page' => $page]);
        $request = $this->client->get(BASE_API_URL . '/search/' . $type . '?' . $queryParams, $authHeaders);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 200, 'Status of response should be 200!');
        $this->assertTrue(isset($decodedResponse['result']) && count($decodedResponse['result']) == 0,
            'Data regarding result should be empty!');
    }
}