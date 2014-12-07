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
} 