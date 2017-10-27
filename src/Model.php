<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

/**
 * Wrapper on catfan/Medoo
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class Model
{
    /*
     * Children data
     * =========================================================================
     */

    /**
     * Database name key in the config file
     *
     * @const string
     */
    const DATABASE_NAME_KEY = '';

    /**
     * Tables the model expects to exist
     *
     * Each item is 'table' => 'columns'[]
     *
     * @const array[]
     */
    const TABLES = [];

    /**
     * Keeps fetched data
     *
     * @var mixed[]
     */
    protected $data;

    /**
     * Tells if they are valid
     *
     * @var boolean
     */
    protected $valid;

    /*
     * Basic methods
     * =========================================================================
     */

    /**
     * Returns which properties should be serialized
     *
     * @return string[]
     */
    public function __sleep()
    {
        return ['data', 'valid'];
    }

    /**
     * Returns a database connection
     *
     * @return \Medoo\Medoo
     */
    public static function getDatabase()
    {
        return MedooFactory::getInstance(static::DATABASE_NAME_KEY);
    }

    /**
     * Returns the stored data
     *
     * @return mixed[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns model's Id
     *
     * @return integer If model has an Id
     * @return null    If Id is not found
     */
    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    /**
     * Tells if the model is valid
     *
     * @return boolean If model has valid data
     * @return null    If validation is not implemented
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Clears model data
     */
    protected function reset()
    {
        $this->data = $this->valid = null;
    }

    /**
     * Reads an entry from the Database
     *
     * @param string  $table Key for TABLES
     * @param mixed[] $where \Medoo\Medoo where clause
     *
     * @return mixed[] Fetched data
     */
    protected function readEntry($table, $where)
    {
        $database = $this->getDatabase();
        return $database->get($table, static::TABLES[$table], $where);
    }

    /*
     * CRUD methods
     * =========================================================================
     */

    /**
     * Creates a new entry in the Database
     *
     * @param mixed[] $data Any required data for the new entry. Keys should
     *                      match COLUMNS_CREATE values
     *
     * @return boolean For success or failure
     */
    abstract public function create($data = null);

    /**
     * Reads from Database into the model
     *
     * It MAY remove data from previous read()
     *
     * @param mixed[] $where \Medoo\Medoo where clause
     *
     * @return boolean For success or failure
     */
    abstract public function read($where);

    /**
     * Updates an entry in the Database with model's data
     *
     * @return boolean For success or failure
     */
    abstract public function update();

    /**
     * Removes model's entry in the Database
     *
     * @return boolean For success or failure
     */
    abstract public function delete();
}
