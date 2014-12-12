<?php
namespace App\Services;

use App\Interfaces\ServiceInterface;
use App\Interfaces\CurlInterface;
use App\ServiceContainer;

/**
 * Class Curl
 * @package App\Services
 *
 * Curl wrapper
 */
class Curl implements ServiceInterface, CurlInterface
{
    /**
     * Url to query
     *
     * @var string
     */
    private $url;

    /**
     * Response body
     *
     * @var string
     */
    private $response;

    /**
     * Response code
     *
     * @var int
     */
    private $responseCode;

    /**
     * Request headers
     *
     * @var []
     */
    private $headers;

    /**
     * @var resource
     */
    private $handler;

    /**
     * Should return unique name of the service
     *
     * @return string
     */
    public static function getServiceName()
    {
        return 'Curl';
    }

    /**
     * Add service initializer into DI container
     *
     * @param ServiceContainer $container
     * @param mixed $injection - injectable object, default null
     */
    public static function initializeService(ServiceContainer $container, $injection = null)
    {
        $className = __CLASS__;
        $container->set(static::getServiceName(), function () use ($className) {
            return new $className;
        });
    }

    protected function __construct()
    {

    }

    /**
     * Will do curl POST on specified URL with specified data
     *
     * @param string|null $url - url, if set will use this one instead of object url
     * @param array $data - data to post
     *
     * @return $this
     */
    public function post($url = null, array $data = [])
    {
        $this->init($url);

        curl_setopt($this->handler, CURLOPT_POST, count($data));
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, http_build_query($data));

        return $this->exec();
    }

    /**
     * Will do curl GET on specified URL
     *
     * @param string|null $url - url, if set will use this one instead of object url
     *
     * @return $this
     */
    public function get($url = null)
    {
        return $this->init($url)->exec();
    }

    /**
     * Method init needed curl options
     *
     * @param string|null $url - url, if set will use this one instead of object url
     *
     * @return $this
     */
    private function init($url = null)
    {
        if ($url !== null) {
            $this->url = $url;
        }

        $this->handler = curl_init();

        curl_setopt($this->handler, CURLOPT_URL, $this->url);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);

        return $this;
    }

    /**
     * Method execute curl command and fills response
     *
     * @return $this
     */
    private function exec()
    {
        if (!empty($this->headers)) {
            curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->headers);
        }

        $this->response = curl_exec($this->handler);
        $this->responseCode = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);

        curl_close($this->handler);

        return $this;
    }

    /**
     * Returns response body as object or array
     *
     * @return mixed
     */
    public function getParsedResponse()
    {
        return json_decode($this->response);
    }

    /**
     * Returns URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set URL
     *
     * @param string $url - url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Returns response body
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns response code
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * Method add header
     *
     * @param string $header
     *
     * @return $this
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Method returns current headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Method clears headers
     *
     * @return $this
     */
    public function clearHeaders()
    {
        $this->headers = [];
        return $this;
    }
}