<?php
namespace tests\MusicPlayer;

use Guzzle\Http\Client;

class MusicPlayerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new Client('http://' . WEB_SERVER_HOST .':' . WEB_SERVER_PORT);
    }

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