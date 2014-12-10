<?php
namespace App\Interfaces;

/**
 * Interface CacheInterface
 * @package App\Interfaces
 *
 * Cache interface
 */
interface CacheInterface
{
    /**
     * Set value to the cache
     *
     * @param string $key
     * @param string $value
     * @param int|null $expiresIn - in how many seconds value should be expired, default null
     * @return CacheInterface
     */
    public function set($key, $value, $expiresIn = null);

    /**
     * Get value by key from the cache
     *
     * @param string $key
     *
     * @return string
     */
    public function get($key);

    /**
     * Removed specified key from the cache
     *
     * @param string $key
     *
     * @return int - the number of removed keys
     */
    public function del($key);

    /**
     * Checks if specified key is in cache
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists($key);

    /**
     * Set prefix for the cache
     *
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix);

    /**
     * Method clear all cache for current prefix
     */
    public function clearAll();

    /**
     * Method clear cache for current prefix by pattern
     *
     * @param string $pattern
     */
    public function clear($pattern);
} 