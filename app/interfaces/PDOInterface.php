<?php
namespace app\interfaces;

/**
 * Interface PDOInterface
 * @package app\interfaces
 *
 * Interface for DB objects
 */
interface PDOInterface
{
    /**
     * PDOs prepare method
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function prepare($statement);

    /**
     * PDOs query method
     *
     * @param string $statement
     * @return \PDOStatement
     */
    public function query($statement);

    /**
     * PDOs execute method
     *
     * @param string $statement
     * @return int
     */
    public function exec($statement);

    /**
     * Will return last inserted id
     *
     * @return int
     */
    public function lastInsertId();
} 