<?php
namespace app;

/**
 * Class BaseContainer
 * @package app
 *
 * Base Container
 */
abstract class BaseContainer extends GetterSetter
{
    /**
     * @param \stdClass $object - object to parse into container
     */
    public function __construct(\stdClass $object)
    {
        $this->parse($object);
    }

    /**
     * Method to parse provided object's properties into current class
     *
     * @param \stdClass $object
     * @return $this
     */
    public function parse(\stdClass $object)
    {
        foreach($this as $property => $value) {
            $underscorePropertyName = strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $property));

            if (isset($object->$underscorePropertyName)) {
                $this->$property = $object->$underscorePropertyName;
            }
        }

        return $this;
    }
} 