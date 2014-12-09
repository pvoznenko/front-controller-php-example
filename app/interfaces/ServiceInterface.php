<?php
namespace App\Interfaces;

use App\ServiceContainer;

/**
 * Interface ServiceInterface
 * @package App\Interfaces
 *
 * Interface for services
 */
interface ServiceInterface
{

    /**
     * Should return unique name of the service
     *
     * @return string
     */
    public static function getServiceName();

    /**
     * Add service initializer into DI container
     *
     * @param ServiceContainer $container
     * @param mixed $injection - injectable object, default null
     */
    public static function initializeService(ServiceContainer $container, $injection = null);
}