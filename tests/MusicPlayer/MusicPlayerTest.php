<?php
namespace tests\MusicPlayer;

use tests\BaseWebTestClass;

class MusicPlayerTest extends BaseWebTestClass
{
    /**
     * Test possibility to get auth token from server
     *
     * @covers \MusicPlayer\controllers\UsersController::authentication
     */
    public function testUserAuthentication()
    {
        $request = $this->client->post('users/authentication', ['Accept' => 'application/json']);
        $response = $request->send();
        $decodedResponse = $response->json();
        $this->assertEquals($response->getStatusCode(), 201);
        $this->assertTrue(isset($decodedResponse['token']));
    }
}