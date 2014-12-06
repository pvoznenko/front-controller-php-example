<?php
namespace app;

/**
 * Class BaseModel
 * @package app
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