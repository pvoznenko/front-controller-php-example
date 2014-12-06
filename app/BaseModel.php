<?php
namespace app;

/**
 * Class BaseModel
 * @package app
 *
 * Base Model object for DB models
 */
abstract class BaseModel
{
    /**
     * Database handler
     *
     * @var DB
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
        $this->db = DB::getInstance();
    }
} 