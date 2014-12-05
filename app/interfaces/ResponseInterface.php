<?php
namespace app\interfaces;

/**
 * Interface ResponseInterface
 * @package interfaces
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
     * @param string $message - message to output, default empty
     *
     * @return bool - if headers already sent will return false, otherwise true
     */
    public function send($message = '');

    /**
     * Checks if requested mime type supported by response
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function isRequestedMimeSupported(RequestInterface $request);
} 