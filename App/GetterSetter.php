<?php
namespace App;

/**
 * Class GetterSetter
 * @package app
 *
 * Class provides getters and setters through magic
 */
abstract class GetterSetter
{
    /**
     * Method for calling methods set{Property Name} and get{Property Name}
     *
     * @param string $methodName
     * @param mixed $args
     * @return null|mixed
     * @throws \Exception
     */
    public function __call($methodName, $args)
    {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!property_exists($this, $property)) {
                throw new \Exception('Property ' . $property . ' not exists');
            }
            switch ($matches[1]) {
                case 'set':
                    $this->checkArguments($args, 1, 1, $methodName);
                    return $this->set($property, $args[0]);
                case 'get':
                    $this->checkArguments($args, 0, 0, $methodName);
                    return $this->get($property);
                default:
                    throw new \Exception('Method ' . $methodName . ' not exists');
            }
        }

        return null;
    }

    /**
     * Getter
     *
     * @param string $property
     * @return mixed
     */
    protected function get($property)
    {
        return $this->$property;
    }

    /**
     * Setter
     *
     * @param mixed $property
     * @param string $value
     * @return $this
     */
    protected function set($property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    /**
     * Method checks if full amount of arguments was invoke
     *
     * @param array $args
     * @param int $min
     * @param int $max
     * @param string $methodName
     * @throws \Exception
     */
    protected function checkArguments(array $args, $min, $max, $methodName)
    {
        $argc = count($args);
        if ($argc < $min || $argc > $max) {
            throw new \Exception('Method ' . $methodName . ' needs minimal ' . $min . ' and maximal ' . $max . ' arguments. ' . $argc . ' arguments given.');
        }
    }
}