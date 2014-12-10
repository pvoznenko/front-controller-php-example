<?php
namespace App\Interfaces;

/**
 * Interface RequestInterface
 * @package interfaces
 *
 * Request encapsulates an incoming URI along with an array of parameters.
 */
interface RequestInterface
{
    /**
     * Returns requested uri
     *
     * @return string
     */
    public function getUri();

    /**
     * Returns requested method, like GET, POST, etc.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Sel list of params
     *
     * @param array $params
     * @return RequestInterface
     */
    public function setParams(array $params);

    /**
     * Get list of params
     *
     * @param array
     */
    public function getParams();

    /**
     * Get list of raw data from the request
     *
     * @param array
     */
    public function getRawData();

    /**
     * Get list of filtered data from the request
     *
     * @param array
     */
    public function getData();

    /**
     * Returns requested $key from the data in request
     *
     * @param string $key
     * @param bool $getRawData - default false, if true will return value from not filtered data, possible XSS
     *
     * @throws \InvalidArgumentException - if key or request method not exists
     *
     * @return string
     */
    public function get($key, $getRawData = false);
} 