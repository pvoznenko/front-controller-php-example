<?php
namespace app\services;

use app\interfaces\PDOInterface;
use app\interfaces\ServiceInterface;
use app\ServiceContainer;

/**
 * Class DB
 * @package app
 *
 * Class DB handle connection
 */
class DB implements ServiceInterface, PDOInterface
{
    /**
     * @var \PDO
     */
    private $db;

    /**
     * Should return unique name of the service
     *
     * @return string
     */
    public static function getServiceName()
    {
        return 'DB';
    }

    /**
     * Add service initializer into DI container
     *
     * @param ServiceContainer $container
     * @param mixed $injection - injectable object, default null
     */
    public static function initializeService(ServiceContainer $container, $injection = null)
    {
        $className = __CLASS__;
        $container->set(static::getServiceName(), function() use($className, $injection) { return new $className($injection); });
    }

    /**
     * Lets set connection to the Storage, in scope of test application it will be sqlite
     *
     * @param \PDO $db - connection to the Database
     */
    protected function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        /**
         * This will create DB, easy solution in scope of our test application.
         */
        $this->db->exec(file_get_contents(ROOT . '/app/config/migration/base.sql'));
    }

    /**
     * PDOs prepare method
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function prepare($statement)
    {
        return $this->db->prepare($statement);
    }

    /**
     * PDOs query method
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function query($statement)
    {
        return $this->db->query($statement);
    }

    /**
     * PDOs execute method
     *
     * @param string $statement
     * @return int
     */
    public function exec($statement)
    {
        return $this->db->exec($statement);
    }

    /**
     * Will return last inserted id
     *
     * @return int
     */
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}