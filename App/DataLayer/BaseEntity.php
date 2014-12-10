<?php
namespace App\DataLayer;

use App\Interfaces\PDOInterface;
use App\ServiceContainer;

/**
 * Class BaseEntity
 * @package App\DataLayer
 *
 * Responsible for communication with DB
 */
class BaseEntity
{
    /**
     * Default limit for selection rows from DB
     */
    const DEFAULT_ROWS_LIMIT = 20;

    /**
     * Database handler
     *
     * @var PDOInterface
     */
    protected $db;

    /**
     * Table name
     *
     * @var string
     */
    protected $tableName;

    public function __construct()
    {
        $this->db = ServiceContainer::getInstance()->get('DB');
    }

    /**
     * Method returns string like: LIMIT ?, ?
     *
     * @param int $offset - data offset, default 0
     * @return string
     */
    protected function getLimitString($offset = 0)
    {
        return sprintf(' LIMIT %d, %d', $offset, self::DEFAULT_ROWS_LIMIT);
    }

    /**
     * Method will prepare income parameters for the insert and return correct values
     *
     * @param Param[] $data
     * @return array - array with prepared data for the insert. [$what, $values]
     */
    private function prepareDataForInsert(array $data)
    {
        $keys = array_keys($data);
        $what = implode(',', $keys);

        $keysPrepared = array_map(function($value) {
            return ':' . $value;
        }, $keys);

        $values = implode(',', $keysPrepared);

        return [$what, $values];
    }

    /**
     * Method binds values
     *
     * @param \PDOStatement $statement - object sent by link
     * @param Param[] $data - data to bind
     */
    private function bindValues(\PDOStatement $statement, array $data)
    {
        foreach($data as $key => $values) {
            $statement->bindValue(':' . $key, $values->getData(), $values->getType());
        }
    }

    /**
     * Method for inserting data
     *
     * @param Param[] $data - object with parameters
     *
     * @return bool|int - will return last inserted id or false on error
     */
    protected function insertData(array $data)
    {
        list($what, $values) = $this->prepareDataForInsert($data);

        $query = 'INSERT INTO `' . $this->tableName . '` (' . $what . ') VALUES (' . $values . ')';

        $statement = $this->db->prepare($query);

        $this->bindValues($statement, $data);

        if ($statement->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Method will prepare income parameters for the WHERE and return statement
     *
     * @param Param[] $data - object with parameters
     * @param string $delimiter - delimiter of parameters, default ' AND '
     *
     * @return string - statement as 'id = :id AND another = :another ...'
     */
    private function prepareWhereData(array $data, $delimiter = ' AND ')
    {
        $keys = array_keys($data);

        $keysPrepared = array_map(function($value) {
            return $value . ' = :' . $value;
        }, $keys);

        return implode($delimiter, $keysPrepared);
    }

    /**
     * Method delete specified data
     *
     * @param Param[] $data - object with parameters
     * @return bool
     */
    public function deleteData(array $data)
    {
        $where = $this->prepareWhereData($data);
        $statement = $this->db->prepare('DELETE FROM `' . $this->tableName . '` WHERE ' . $where);
        $this->bindValues($statement, $data);

        return $statement->execute();
    }

    /**
     * Method will return id by parameters
     *
     * @param Param[] $data - object with parameters
     * @return bool|int - if successful you will get id, otherwise false
     */
    public function getId(array $data)
    {
        $where = $this->prepareWhereData($data);
        $statement = $this->db->prepare('SELECT id FROM `' . $this->tableName . '` WHERE ' . $where);
        $this->bindValues($statement, $data);

        if ($statement->execute()) {
            return (int)$statement->fetch(\PDO::FETCH_COLUMN);
        }

        return false;
    }

    /**
     * Method return entity by specified parameters
     *
     * @param array $what - array of keys what to select parameters, what to return
     * @param Param[] $where - object with parameters, where
     * @param int $fetchMode - PDO fetch mode, default \PDO::FETCH_ASSOC
     * @param int|null $offset - data offset for pagination. If set, will make query with LIMIT. by default null
     *
     * @return mixed|bool - if successful will data specified by $fetchMode, otherwise false
     */
    public function selectData(array $what, array $where, $fetchMode = \PDO::FETCH_ASSOC, $offset = null)
    {
        $data = $where;
        $where = $this->prepareWhereData($where);

        $query = 'SELECT ' . implode(', ', $what) . ' FROM `' . $this->tableName . '` WHERE ' . $where;

        if ($offset !== null) {
            $query .= $this->getLimitString($offset);
        }

        $statement = $this->db->prepare($query);

        $this->bindValues($statement, $data);

        if ($statement->execute()) {
            return $statement->fetch($fetchMode);
        }

        return false;
    }

    /**
     * Method updates specified parameters
     *
     * @param Param[] $what - object with parameters, what to update
     * @param Param[] $where - object with parameters, where
     * @return bool
     */
    public function updateData(array $what, array $where)
    {
        $data1 = $what;
        $data2 = $where;
        $what = $this->prepareWhereData($what, ', ');
        $where = $this->prepareWhereData($where);

        $statement = $this->db->prepare('UPDATE `' . $this->tableName . '` SET ' . $what . ' WHERE ' . $where);
        $this->bindValues($statement, $data1);
        $this->bindValues($statement, $data2);

        return $statement->execute();
    }
} 