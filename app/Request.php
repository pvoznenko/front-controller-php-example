<?php
namespace app;

use app\exceptions\NotAcceptableException;
use app\interfaces\RequestInterface;

/**
 * Class Request
 */
class Request implements RequestInterface
{
    /**
     * Requested uri
     *
     * @var string
     */
    protected $uri = '';

    /**
     * Requested method, like GET, POST, etc.
     *
     * @var string
     */
    protected $method = '';

    /**
     * Accept MIME types
     *
     * @var string
     */
    protected $mime = '';

    /**
     * List of parameters in request
     *
     * @var array
     */
    protected $params = [];

    /**
     * @param string $uri - requested uri
     * @param string $method - requested method, like GET, POST, etc.
     * @param string $mime - HTTP Accept MIME type header
     *
     * @throws NotAcceptableException
     */
    public function __construct($uri, $method, $mime)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->mime = $mime;
    }

    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Return requested uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Return requested method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set list of requested parameters
     *
     * @param array $params
     * @return $this|RequestInterface
     */
    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {
            $this->setParam($key, $value);
        }

        return $this;
    }

    /**
     * Set parameter
     *
     * @param string $key - key should be string
     * @param mixed $value
     * @return $this
     */
    public function setParam($key, $value)
    {
        if (is_string($key)) {
            $this->params[$key] = $value;
        }

        return $this;
    }

    /**
     * Get parameter
     *
     * @param string $key
     *
     * @throws \InvalidArgumentException - if parameter not exist
     * @return mixed
     */
    public function getParam($key)
    {
        if (!isset($this->params[$key])) {
            throw new \InvalidArgumentException('The request parameter with key "' . $key . '" is invalid.');
        }

        return $this->params[$key];
    }

    /**
     * Get list of parameters
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}