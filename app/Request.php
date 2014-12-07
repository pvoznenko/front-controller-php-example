<?php
namespace app;

use app\exceptions\NotAcceptableException;
use app\interfaces\RequestInterface;

/**
 * Class Request
 *
 * The Request class encapsulates an incoming URI along with an array of parameters.
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
     * List of parameters in request
     *
     * @var array
     */
    protected $params = [];

    /**
     * List of raw request data
     *
     * @var array
     */
    protected $rawData = [];

    /**
     * @param string $uri - requested uri
     * @param string $method - requested method, like GET, POST, etc.
     * @param array $rawData - list of raw request data
     */
    public function __construct($uri, $method, array $rawData)
    {
        $this->uri = $uri;
        $this->method = $method;

        /**
         * Since PUT not in PHP global variables we need to read stream by our self
         */
        if ($method === 'PUT') {
            parse_str(file_get_contents('php://input'), $rawData['PUT']);
        }

        $this->rawData = $rawData;
    }

    /**
     * Get list of raw data from the request
     *
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
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