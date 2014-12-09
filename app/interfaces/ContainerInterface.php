<?php
namespace app\interfaces;

/**
 * Interface ContainerInterface
 * @package app\interfaces
 *
 * Container interface
 */
interface ContainerInterface
{
    /**
     * @param \stdClass $object - object to parse into container
     */
    public function __construct(\stdClass $object);

    /**
     * Method to parse provided object's properties into current class
     *
     * @param \stdClass $object
     * @return $this
     */
    public function parse(\stdClass $object);
} 