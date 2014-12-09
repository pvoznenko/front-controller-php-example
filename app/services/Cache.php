<?php
namespace App\Services;

use App\Interfaces\ServiceInterface;
use App\ServiceContainer;
use App\Interfaces\CacheInterface;

class Cache implements ServiceInterface, CacheInterface
{
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
        $container->set(static::getServiceName(), function() use($className) { return new $className; });
    }

    protected function __construct()
    {
    }

    /**
     * Set value to the cache
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function set($key, $value)
    {
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
        return '';
    }

    /**
     * Removed specified key from the cache
     *
     * @param string $key
     *
     * @return $this
     */
    public function clear($key)
    {
        return $this;
    }

    /**
     * Checks if specified key is in cache
     *
     * @param string $key
     *
     * @return bool
     */
    public function isPresent($key)
    {
        return false;
    }
}