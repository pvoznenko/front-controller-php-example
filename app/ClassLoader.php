<?php
namespace app;

/**
 * Class ClassLoader
 *
 * Provides class loading
 */
class ClassLoader
{
    const MODULE_PREFIX = 'src';

    /**
     * Filesystem prefix for class files
     *
     * @var string
     */
    protected static $prefix = '';

    /**
     * Method to register our custom auto loader
     *
     * @param string $prefix - filesystem prefix for class files
     */
    public static function register($prefix = '')
    {
        self::$prefix = rtrim($prefix, '/') . '/';
        spl_autoload_register(__CLASS__ . '::loadClass');
    }

    /**
     * Method for class loading
     *
     * @param string $className - class name, with namespace
     *
     * @return bool - returns true if class exists
     */
    public static function loadClass($className)
    {
        $filePath = self::$prefix . self::getFilePathByClassName($className) . '.php';
        $moduleFilePath = self::$prefix . self::MODULE_PREFIX . '/' . self::getFilePathByClassName($className) . '.php';

        if (is_readable($filePath)) {
            require $filePath;
        } else if (is_readable($moduleFilePath)) {
            require $moduleFilePath;
        }

        return class_exists($className);
    }

    /**
     * Method converts namespace to path
     *
     * @param string $className
     *
     * @return string
     */
    public static function getFilePathByClassName($className)
    {
        return implode(explode('\\', $className), DIRECTORY_SEPARATOR);
    }
}