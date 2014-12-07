<?php
namespace app;

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
class DB extends Singleton
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Lets set connection to the Storage, in scope of test application it will be sqlite
     */
    protected function __construct()
    {
        $this->db = new \PDO('sqlite:' . DB_FILE_PATH);
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