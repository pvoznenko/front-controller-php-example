<?php
namespace app\interfaces;

use app\ServiceContainer;

/**
 * Interface ServiceInterface
 * @package app\interfaces
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
     * @return mixed
     */
    public static function initializeService(ServiceContainer $container, $injection = null);
}