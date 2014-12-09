<?php
namespace App;

/**
 * Class ServiceContainer
 * @package app
 *
 * Service container
 */
class ServiceContainer extends Singleton
{
    /**
     * List of initialized services
     *
     * @var array
     */
    protected $services = [];

    /**
     * Add new service to container
     *
     * @param string $serviceName - service name
     * @param callable $initializer - lambda that will be executed when service will be called
     * @throws \Exception
     */
    public function set($serviceName, \Closure $initializer)
    {
        $this->services[$serviceName] = $initializer;
    }

    /**
     * Tells if container has the service registered
     *
     * @param string $serviceName
     * @return bool
     */
    public function has($serviceName)
    {
        return isset($this->services[$serviceName]);
    }

    /**
     * Return service instance, create if necessary
     *
     * @param string $serviceName
     * @return mixed
     */
    public function get($serviceName)
    {
        if (!$this->has($serviceName)) {
            throw new \InvalidArgumentException(sprintf('Service %s not defined!', $serviceName));
        }

        if (is_callable($this->services[$serviceName])) {
            return $this->services[$serviceName]($this);
        }

        return $this->services[$serviceName];
    }
} 