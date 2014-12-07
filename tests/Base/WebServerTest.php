<?php
namespace tests\Base;

use tests\BaseWebTestClass;
use \Guzzle\Http\Exception\BadResponseException;

class WebServerTest extends BaseWebTestClass
{
    /**
     * Test 404 from the web server
     */
    public function testNotFound()
    {
        try {
            $this->client->get('not-existing-url', ['Accept' => 'application/json'])->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 404);
        }
    }

    /**
     * Test not acceptable exception from the web server, should have 406
     */
    public function testNotAcceptable()
    {
        try {
            $this->client->get('some-url', ['Accept' => 'text/xml'])->send();
        } catch (BadResponseException $exception) {
            $this->assertEquals($exception->getResponse()->getStatusCode(), 406);
        }
    }
} 