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
     */
    const REDIS_PREFIX = 'MP:';

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
        $container->set(static::getServiceName(), function() use($className, $injection) { return new $className($injection); });
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
        $expireResolution = $expiresIn;

        if ($expiresIn != null) {
            $expireResolution += time();
        }

        $this->client->set(self::REDIS_PREFIX . $key, $value, $expireResolution);
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
        return $this->client->get(self::REDIS_PREFIX . $key);
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
        return $this->client->del(self::REDIS_PREFIX . $key);
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
        return (bool)$this->client->exists(self::REDIS_PREFIX . $key);
    }
}