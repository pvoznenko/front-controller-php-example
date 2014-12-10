<?php
namespace App\Interfaces;


interface CacheContainerInterface
{

    /**
     * @param mixed $object - object to parse into container
     */
    public function __construct($object);

    /**
     * Method to parse provided object's properties into current class
     *
     * @param mixed $object
     * @return $this
     */
    public function parse($object);
} 