<?php
namespace app;

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
     * @param array|string $serviceName
     * @param callable|string|null $initializer
     * @throws \Exception
     */
    public function set($serviceName, $initializer = null)
    {
        if (is_object($initializer)) {
            $this->services[$serviceName] = $initializer;
        } else {
            throw new \Exception('Wrong set of parameters');
        }
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
        return $this->services[$serviceName];
    }
} 