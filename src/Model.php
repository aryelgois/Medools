<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use aryelgois\Utils\Utils;

/**
 * Wrapper on catfan/Medoo
 *
 * Each model class maps to one Table in the Database, and each model object
 * maps to one row.
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class Model
{
    /*
     * Model configuration
     * =========================================================================
     */

    /**
     * Database name key in the config file
     *
     * @const string
     */
    const DATABASE_NAME_KEY = 'default';

    /**
     * Database Table the model works with
     *
     * The recomended is to use a plural name for the table and it's singular in
     * the model name
     *
     * @const string
     */
    const TABLE = '';

    /**
     * Columns the model expects to exist
     *
     * @const string[]
     */
    const COLUMNS = ['id'];

    /**
     * Primary Key column
     *
     * @const string[]
     */
    const PRIMARY_KEY = ['id'];

    /**
     * Auto Increment column
     *
     * This column is ignored by update()
     *
     * @const string|null
     */
    const AUTO_INCREMENT = 'id';

    /**
     * If create(), update() and delete() are disabled
     *
     * @const boolean
     */
    const READ_ONLY = false;

    /**
     * If delete() actually removes the row or if it changes a column
     *
     * @const string|null Column affected by the soft delete
     */
    const SOFT_DELETE = null;

    /**
     * How the soft delete works
     *
     * Possible values:
     * - deleted: 0 or 1
     * - active:  1 or 0
     * - stamp:   null or current timestamp
     *
     * @const string
     */
    const SOFT_DELETE_MODE = 'deleted';

    /*
     * Model data
     * =========================================================================
     */

    /**
     * Changes done by set() to be saved by update()
     *
     * @var mixed[]
     */
    protected $changes = [];

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
     * Creates a new Model object
     *
     * @param mixed $where @see load(). If null, a fresh model is created
     *
     * @throws \InvalidArgumentException @see load()
     */
    public function __construct($where = null)
    {
        if ($where !== null) {
            $this->load($where);
        }
    }

    /**
     * Cleans data keys, removing unwanted columns
     *
     * @todo Ignore auto timestamp column
     * @todo Option to tell custom ignored columns
     *
     * @param string[] $data Data to be cleaned
     * @param string   $data Which method will use the result
     *
     * @return string[]
     */
    protected static function dataCleanup($data)
    {
        $whitelist = static::COLUMNS;
        $blacklist = [static::AUTO_INCREMENT];

        $data = Utils::arrayWhitelist($data, $whitelist);
        $data = Utils::arrayBlacklist($data, $blacklist);
        return $data;
    }

    /**
     * Tells if the model is valid
     *
     * @return boolean If model has valid data
     * @return null    If validation is not implemented or the model is empty
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Returns the stored data in a column
     *
     * @param string $column A known column
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException If $column is unknown
     */
    public function get($column)
    {
        if (!in_array($column, static::COLUMNS)) {
            throw new \InvalidArgumentException('Unknown column');
        }
        return $this->changes[$column] ?? $this->data[$column];
    }

    /**
     * Selects Current Timestamp from Database
     *
     * Useful to keep timezone consistent
     *
     * @return string
     */
    public static function getCurrentTimestamp()
    {
        $database = self::getDatabase();
        $sql = 'SELECT CURRENT_TIMESTAMP';
        return $database->query($sql)->fetch(\PDO::FETCH_NUM)[0];
    }

    /**
     * Returns the stored data
     *
     * @return mixed[]
     */
    public function getData()
    {
        return array_replace($this->data, $this->changes);
    }

    /**
     * Returns a database connection
     *
     * @return \Medoo\Medoo
     */
    final public static function getDatabase()
    {
        return MedooConnection::getInstance(static::DATABASE_NAME_KEY);
    }

    /**
     * Returns model's Primary Key
     *
     * NOTE:
     * - It returns the data saved in Database, changes by set() are ignored
     *
     * @return mixed[] Usually it will contain an integer key
     */
    public function getPrimaryKey()
    {
        return Utils::arrayWhitelist($this->data, static::PRIMARY_KEY);
    }

    /**
     * Reloads model data
     *
     * @return boolean For success or failure
     */
    public function reload()
    {
        return $this->load($this->getPrimaryKey());
    }

    /**
     * Cleans model data
     *
     * @param boolean $validity Value to set into $this->valid
     */
    protected function reset($validity = null)
    {
        $this->changes = [];
        $this->data = null;
        $this->valid = $validity;
    }

    /**
     * Changes the value in a column
     *
     * NOTE:
     * - You should validate the data before calling this method
     * - Changes need to be saved in the Database with save() or update($column)
     *
     * @param string $column A known column
     * @param mixed  $value  The new value
     *
     * @return boolean For success or failure
     *
     * @throws \InvalidArgumentException If $column is unknown
     */
    public function set($column, $value)
    {
        if (static::READ_ONLY) {
            return false;
        }

        if (!in_array($column, static::COLUMNS)) {
            throw new \InvalidArgumentException('Unknown column');
        }
        $this->changes[$column] = $value;

        return true;
    }

    /*
     * CRUD methods
     * =========================================================================
     */

    /**
     * Creates a new row in the Table or updates it with new data
     *
     * @return boolean For success or failure
     */
    public function save()
    {
        if (static::READ_ONLY || empty($this->changes)) {
            return false;
        }

        $data = $this->changes;
        $database = self::getDatabase();
        $stmt = ($this->data === null)
              ? $database->insert(static::TABLE, static::dataCleanup($data))
              : $database->update(static::TABLE, $data, $this->getPrimaryKey());

        if ($stmt->errorCode() == '00000') {
            if ($this->data === null) {
                /*
                 * It is prefered to load back because the Database may apply
                 * default values or alter some columns
                 *
                 * First, get the AUTO_INCREMENT
                 * Then, extract the PRIMARY_KEY
                 * Finally, load from Database
                 */
                $column = static::AUTO_INCREMENT;
                if ($column !== null) {
                    $data[$column] = $database->id();
                }
                $where = Utils::arrayWhitelist($data, static::PRIMARY_KEY);
                return $this->load($where);
            }
            $this->changes = [];
            $this->data = array_replace($this->data, $data);
            $this->valid = true;
        } else {
            $this->valid = false;
        }

        return $this->valid;
    }

    /**
     * Loads a row from Table into the model
     *
     * @param mixed $where Value for Primary Key or \Medoo\Medoo where clause
     *
     * @return boolean For success or failure
     *
     * @throws \InvalidArgumentException If could not solve Primary Key:
     *                                   - $where does not specify columns and
     *                                     does not match PRIMARY_KEY length
     */
    public function load($where)
    {
        /*
         * Preprocess $where
         *
         * It allows the use of a simple value (e.g. string or integer) or a
         * simple array without specifing the PRIMARY_KEY column(s)
         */
        $where = (array) $where;
        if (!Utils::arrayIsAssoc($where)) {
            $where = @array_combine(static::PRIMARY_KEY, $where);
            if ($where === false) {
                throw new \InvalidArgumentException(
                    'Could not solve Primary Key'
                );
            }
        }

        $this->reset(false);

        $database = self::getDatabase();
        $data = $database->get(static::TABLE, static::COLUMNS, $where);
        if ($data) {
            $this->data = $data;
            $this->valid = true;
        }

        return $this->valid;
    }

    /**
     * Selectively updates the model's row in the Database
     *
     * @param string|string[] $columns Specify which columns to update
     *
     * @return boolean For success or failure
     */
    public function update($columns)
    {
        if (static::READ_ONLY) {
            return false;
        }

        $columns = (array) $columns;
        $data = Utils::arrayWhitelist($this->changes, $columns);

        $database = self::getDatabase();
        $stmt = $database->update(static::TABLE, $data, $this->getPrimaryKey());
        if ($stmt->errorCode() == '00000') {
            $this->changes = Utils::arrayBlacklist($this->changes, $columns);
            $this->data = array_replace($this->data, $data);
            $this->valid = true;
        } else {
            $this->valid = false;
        }

        return $this->valid;
    }

    /**
     * Removes model's row from the Table
     *
     * @return boolean For success or failure
     *
     * @throws \UnexpectedValueException If SOFT_DELETE_MODE is unknown
     */
    public function delete()
    {
        if (static::READ_ONLY) {
            return false;
        }

        $database = self::getDatabase();
        $column = static::SOFT_DELETE;
        if ($column) {
            switch (static::SOFT_DELETE_MODE) {
                case 'deleted':
                    $this->set($column, 1);
                    break;

                case 'active':
                    $this->set($column, 0);
                    break;

                case 'stamp':
                    $this->set($column, static::getCurrentTimestamp());
                    break;

                default:
                    throw new \UnexpectedValueException('Unknown mode');
                    break;
            }
            return $this->update($column);
        } else {
            $stmt = $database->delete(static::TABLE, $this->getPrimaryKey());
            $this->reset();
            return ($stmt->rowCount() > 0);
        }
    }
}
