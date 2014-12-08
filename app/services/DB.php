<?php
namespace app\services;

use app\interfaces\ServiceInterface;
use app\ServiceContainer;

/**
 * Class DB
 * @package app
 *
 * Class DB handle connection
 *
 * @method \PDOStatement prepare(string $statement) - PDOs prepare method
 * @method \PDOStatement query(string $statement) - PDOs query method
 * @method int exec(string $statement) - PDOs execute method
 * @method int lastInsertId() - PDOs last insert id method
 */
class DB implements ServiceInterface
{
    /**
     * @var \PDO
     */
    protected $db;

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
     * @return mixed
     */
    public static function initializeService(ServiceContainer $container, $injection = null)
    {
        $container->set(static::getServiceName(), new self($injection));
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
     * Magic method to get access to the PDOs methods
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->db, $method], $params);
    }
}