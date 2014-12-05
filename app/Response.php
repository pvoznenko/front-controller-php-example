<?php

namespace app;

use app\interfaces\RequestInterface;
use app\interfaces\ResponseInterface;

/**
 * Class Response
 */
class Response implements ResponseInterface
{
    /**
     * List of supported MIME types
     *
     * @var array
     */
    protected $supportedMimeTypes = ['*/*', 'application/json'];

    /**
     * List of headers
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Version of API
     *
     * @var string
     */
    protected $version = '';

    /**
     * @param string $version - version like 'HTTP/1.1'
     */
    public function __construct($version)
    {
        $this->version = $version;
    }

    /**
     * Returns version
     *
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }

    /**
     * Adds new header to the response header list
     *
     * @param string $header
     * @return $this
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Adds list of headers to the existing list of response headers
     * @param array $headers
     * @return $this
     */
    public function addHeaders(array $headers)
    {
        foreach ($headers as $header) {
            $this->addHeader($header);
        }

        return $this;
    }

    /**
     * Returns list of existing headers
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Will publish existing headers
     *
     * @param string $message - message to output, default empty
     *
     * @return bool - if headers already sent will return false, otherwise true
     */
    public function send($message = '')
    {
        if (headers_sent()) {
            return false;
        }

        foreach($this->getHeaders() as $header) {
            header($this->getVersion() . ' ' . $header, true);
        }

        return (bool)(print $message);
    }

    /**
     * Checks if requested mime type supported by response
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function isRequestedMimeSupported(RequestInterface $request)
    {
        $acceptMimeType = $request->getMime();

        foreach($this->supportedMimeTypes as $mime) {
            if (strstr($acceptMimeType, $mime) !== false) {
                return true;
            }
        }

        return false;
    }
}