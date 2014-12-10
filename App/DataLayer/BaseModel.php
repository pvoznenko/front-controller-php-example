<?php
namespace App\DataLayer;

use App\ServiceContainer;
use App\Interfaces\CacheInterface;
use App\Containers\CacheDataContainer;

/**
 * Class BaseModel
 * @package App\DataLayer
 */
abstract class BaseModel
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = ServiceContainer::getInstance()->get('Cache');
    }

    /**
     * Get data from cache
     *
     * @param string $key - cache key
     * @return mixed
     */
    protected function getFromCache($key)
    {
        $data = $this->cache->get($key);
        return (new CacheDataContainer($data))->getDataFromJson();
    }

    /**
     * Set data to cache
     *
     * @param string $key - cache key
     * @param mixed $data - data to store
     * @param int|null $expiresIn - in how many seconds value should be expired, default null
     *
     * @return CacheInterface
     */
    protected function setToCache($key, $data, $expiresIn = null)
    {
        $object = (new CacheDataContainer($data))->json();
        return $this->cache->set($key, $object->__toString(), $expiresIn);
    }

    /**
     * Get data from cache if not exists, regenerate it from callback
     *
     * @param string $cacheKey - cache key
     * @param callable $callback - callback method if cache not exist
     *
     * @return mixed
     */
    protected function getData($cacheKey, \Closure $callback)
    {
        $data = $this->getFromCache($cacheKey);

        if (empty($data)) {
            $data = $callback($this);
            $this->setToCache($cacheKey, $data);
        }

        return $data;
    }

    /**
     * Method clears cache and call callback
     *
     * @param string $cacheKeyPattern - cache key pattern
     * @param callable $callback - method will be called after cache is cleared
     * @return mixed
     */
    protected function clearCache($cacheKeyPattern, \Closure $callback)
    {
        $this->cache->clear($cacheKeyPattern);

        return $callback($this);
    }
} 