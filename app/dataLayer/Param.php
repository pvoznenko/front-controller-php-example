<?php
namespace App\DataLayer;

use App\GetterSetter;

/**
 * Class Param
 * @package App\DataLayer
 *
 * Object for parameter for DB
 *
 * @method string getData() - returns data for inserting
 * @method int getType() - returns field type
 */
class Param extends GetterSetter
{
    /**
     * Data for inserting
     *
     * @var string
     */
    protected $data;

    /**
     * Type of field
     *
     * @var int
     */
    protected $type;

    /**
     * @param string $data - Data for inserting
     * @param int $type - Type of field
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }
} 