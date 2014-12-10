<?php
namespace App\Interfaces;

/**
 * Interface ContainerInterface
 * @package App\Interfaces
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