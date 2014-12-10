<?php
namespace App\Containers;

use App\Interfaces\CacheContainerInterface;

/**
 * Class CacheDataContainer
 * @package App\Containers
 *
 * Container for data to store in cache
 */
class CacheDataContainer implements CacheContainerInterface
{
    /**
     * Object
     *
     * @var mixed
     */
    protected $object;

    /**
     * @param mixed $object - object to parse into container
     */
    public function __construct($object)
    {
        $this->parse($object);
    }

    /**
     * Method to parse provided object's properties into current class
     *
     * @param mixed $object
     * @return $this
     */
    public function parse($object)
    {
        $this->object = $object;
        return $this;
    }

    /**
     * Return data from container
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->object;
    }

    /**
     * Return data from container and execute on it json_decode
     *
     * @return mixed
     */
    public function getDataFromJson()
    {
        return json_decode($this->object);
    }

    /**
     * Return data from container and execute on it unserialize
     *
     * @return mixed
     */
    public function getDataFromSerialize()
    {
        return unserialize($this->object);
    }

    /**
     * Method do serialize on current data in container
     *
     * @return $this
     */
    public function serialize()
    {
        $this->object = serialize($this->object);
        return $this;
    }

    /**
     * Method do json_encode on current data in container
     *
     * @return $this
     */
    public function json()
    {
        $this->object = json_encode($this->object);
        return $this;
    }

    /**
     * Set data to container and do on it json_encode
     *
     * @param mixed $object
     * @return CacheDataContainer
     */
    public function setDataToJson($object)
    {
        $this->object = $object;
        return $this->json();
    }

    /**
     * Set data to container and do on it serialize
     *
     * @param mixed $object
     * @return CacheDataContainer
     */
    public function setDataToSerialize($object)
    {
        $this->object = $object;
        return $this->serialize();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->object;
    }
} 