<?php
namespace App\Services;

use App\Interfaces\ServiceInterface;
use App\ServiceContainer;
use App\Interfaces\CacheInterface;
use Predis\Client;

class Cache implements ServiceInterface, CacheInterface
{
    /**
     * Prefix that Redis use to store data
     *
     * @var string
     */
    private $redisPrefix = 'MP:';

    /**
     * Redis client
     *
     * @var Client
     */
    private $client;

    /**
     * Should return unique name of the service
     *
     * @return string
     */
    public static function getServiceName()
    {
        return 'Cache';
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
        $container->set(static::getServiceName(), function () use ($className, $injection) {
            return new $className($injection);
        });
    }

    protected function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set value to the cache
     *
     * @param string $key
     * @param string $value
     * @param int|null $expiresIn - in how many seconds value should be expired, default null
     * @return $this
     */
    public function set($key, $value, $expiresIn = null)
    {
        if (!CACHING) {
            return $this;
        }

        if ($expiresIn !== null) {
            $this->client->setex($this->redisPrefix . $key, $expiresIn, $value);
        } else {
            $this->client->set($this->redisPrefix . $key, $value);
        }

        return $this;
    }

    /**
     * Get value by key from the cache
     *
     * @param string $key
     *
     * @return string
     */
    public function get($key)
    {
        if (!CACHING) {
            return '';
        }

        return $this->client->get($this->redisPrefix . $key);
    }

    /**
     * Removed specified key from the cache
     *
     * @param string $key
     *
     * @return int - the number of removed keys
     */
    public function del($key)
    {
        if (!CACHING) {
            return 0;
        }

        return $this->client->del($this->redisPrefix . $key);
    }

    /**
     * Checks if specified key is in cache
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key)
    {
        if (!CACHING) {
            return false;
        }

        return (bool)$this->client->exists($this->redisPrefix . $key);
    }

    /**
     * Set prefix for the cache
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->redisPrefix = $prefix;
        return $this;
    }

    /**
     * Method clear cache for current prefix by pattern
     *
     * @param string $pattern
     */
    public function clear($pattern)
    {
        if (!CACHING) {
            return;
        }

        $data = $this->client->keys($this->redisPrefix . $pattern);

        foreach ($data as $key) {
            $this->client->del($key);
        }
    }

    /**
     * Method clear all cache for current prefix
     */
    public function clearAll()
    {
        if (!CACHING) {
            return;
        }

        $data = $this->client->keys($this->redisPrefix . '*');

        foreach ($data as $key) {
            $this->client->del($key);
        }
    }
}