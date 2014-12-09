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
     * @return CacheInterface
     */
    public function set($key, $value);

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
     * @return CacheInterface
     */
    public function clear($key);

    /**
     * Checks if specified key is in cache
     *
     * @param string $key
     *
     * @return bool
     */
    public function isPresent($key);
} 