<?php
namespace app\interfaces;

/**
 * Interface RequestInterface
 * @package interfaces
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
     * Get Accept MIME types
     *
     * @param string
     */
    public function getMime();
} 