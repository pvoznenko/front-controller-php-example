<?php
namespace App;

use App\Exceptions\NotAcceptableException;
use App\Interfaces\RequestInterface;

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
     * List of filtered request data
     *
     * @var array
     */
    protected $data = [];

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
     * Get list of filtered data from the request
     *
     * @return array
     */
    public function getData()
    {
        /**
         * lets filter on demand, lazy way
         */
        if (empty($this->data)) {
            $this->data = $this->filterIncomeData($this->rawData, $this->method);
        }

        return $this->data;
    }

    /**
     * Method filters incoming data
     *
     * @param array $data - array with raw data from the client
     * @param string $method - request method
     * @return array
     */
    private function filterIncomeData(array $data, $method)
    {
        if (!isset($data[$method])) {
            return $data;
        }

        foreach($data[$method] as $key => $value) {
            $data[$method][$key] = htmlspecialchars($value, ENT_QUOTES);
        }

        return $data;
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