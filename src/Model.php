<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use Medoo\Medoo;

/**
 * Wrapper on catfan/medoo
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/medoo-wrapper
 */
abstract class Model
{
    /**
     * Database table the object is from
     *
     * Defined by children
     *
     * @const string
     */
    const DATABASE_TABLE = '';

    /**
     * Database connection in a Medoo object
     *
     * @var Medoo\Medoo
     */
    protected $database;

    /**
     * Used by children classes to keep fetched data
     *
     * @var mixed[]
     */
    protected $data;

    /**
     * Used by children classes to tell if they are valid
     *
     * @var boolean
     */
    protected $valid;

    /**
     * Creates a new Database object
     */
    public function __construct()
    {
        $options = static::loadConfig();
        $this->database = new Medoo($options);
    }

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
     * Recreates Medoo object after unserialization
     */
    public function __wakeup()
    {
        self::__construct();
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
     * Returns object's Id
     *
     * @return integer If object was created successfuly and has an Id
     * @return null    If Id is not found
     */
    public function getId()
    {
        return $this->data['id'] ?? null;
    }

    /**
     * Tells if the object is valid
     *
     * @return boolean If object was created successfuly or not
     * @return null    If validation is not implemented
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Load the configuration for Medoo from somewhere (a config file?)
     *
     * @return mixed[] The options ready to be passed to Medoo
     */
    abstract protected static function loadConfig();
}
