<?php
namespace App\Interfaces;

/**
 * Interface ResponseInterface
 * @package interfaces
 *
 * The Response allows to stack up HTTP headers to send them back to the client additionally with json content.
 */
interface ResponseInterface
{

    /**
     * Add header to response
     *
     * @param string $header
     * @return ResponseInterface
     */
    public function addHeader($header);

    /**
     * Will publish existing headers
     *
     * @param array $message - message to output as json
     *
     * @return bool - if headers already sent will return false, otherwise true
     */
    public function send(array $message = null);

    /**
     * Checks if requested mime type supported by response
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function isRequestedMimeSupported(RequestInterface $request);
} 