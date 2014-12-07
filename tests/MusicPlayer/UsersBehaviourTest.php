<?php
namespace tests\MusicPlayer;

use tests\BaseWebTestClass;

/**
 * Class UsersBehaviourTest
 * @package tests\MusicPlayer
 *
 * Here we going to test users API
 */
class UsersBehaviourTest extends BaseWebTestClass
{

    /**
     * Test possibility to get auth token from server
     *
     * @covers \MusicPlayer\controllers\UsersController::authentication
     */
    public function testUserAuthentication()
    {
        $request = $this->client->post('api/users/authentication', ['Accept' => 'application/json']);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 201, 'Status of response should be 201!');
        $this->assertTrue(isset($decodedResponse['token']), 'Token should be presented in response!');
    }
}