<?php
namespace tests;

use Guzzle\Http\Client;

/**
 * Class BaseWebTestClass
 * @package tests
 *
 * Creates client connection for test purpose
 */
class BaseWebTestClass extends \PHPUnit_Framework_TestCase
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
     * Method will return headers with predefined parameters with authentication token for new user
     *
     * @return array
     */
    public function getAuthHeaders()
    {
        $request = $this->client->post(BASE_API_URL . '/users/authentication', ['Accept' => 'application/json']);
        $response = $request->send();
        $decodedResponse = $response->json();
        return ['Accept' => 'application/json', 'token' => $decodedResponse['token']];
    }
} 