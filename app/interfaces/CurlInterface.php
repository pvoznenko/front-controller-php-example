<?php
namespace app\interfaces;

/**
 * Interface CurlInterface
 * @package app\interfaces
 *
 * Interface for Curl Service
 */
interface CurlInterface
{
    /**
     * Will do curl POST on specified URL with specified data
     *
     * @param string|null $url - url, if set will use this one instead of object url
     * @param array $data - data to post
     *
     * @return $this
     */
    public function post($url = null, array $data = []);

    /**
     * Will do curl GET on specified URL
     *
     * @param string|null $url - url, if set will use this one instead of object url
     *
     * @return $this
     */
    public function get($url = null);

    /**
     * Returns response body as object or array
     *
     * @return mixed
     */
    public function getParsedResponse();

    /**
     * Returns URL
     *
     * @return string
     */
    public function getUrl();

    /**
     * Set URL
     *
     * @param string $url - url
     * @return CurlInterface
     */
    public function setUrl($url);

    /**
     * Returns response body
     *
     * @return string
     */
    public function getResponse();

    /**
     * Returns response code
     *
     * @return int
     */
    public function getResponseCode();

    /**
     * Method add header
     *
     * @param string $header
     *
     * @return CurlInterface
     */
    public function addHeader($header);

    /**
     * Method returns current headers
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Method clears headers
     *
     * @return CurlInterface
     */
    public function clearHeaders();
} 