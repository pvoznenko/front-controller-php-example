<?php
namespace Tests\MusicPlayer;

use Tests\BaseWebTestClass;
use \Guzzle\Http\Exception\BadResponseException;

/**
 * Class UsersBehaviourTest
 * @package Tests\MusicPlayer
 *
 * Here we going to test users API
 */
class UsersBehaviourTest extends BaseWebTestClass
{

    /**
     * Test possibility to get auth token from server
     *
     * @covers \MusicPlayer\Controllers\UsersController::authentication
     */
    public function testUserAuthentication()
    {
        try {
            $request = $this->client->post(BASE_API_URL . '/users/authentication', ['Accept' => 'application/json']);
            $response = $request->send();
            $decodedResponse = $response->json();
            $this->assertEquals($response->getStatusCode(), 201, 'Status of response should be 201!');
            $this->assertTrue(isset($decodedResponse['token']), 'Token should be presented in response!');
        } catch (BadResponseException $exception) {
            $this->assertTrue(false, sprintf('Error during authentication: %s :: Code: %s :: Response: %s',
                $exception->getMessage(), $exception->getCode(), $exception->getResponse()->getMessage()));
        }
    }
}