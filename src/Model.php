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
     * @const string|string[]
     */
    const PRIMARY_KEY = 'id';

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
     * Selects Current Timestamp from Database
     *
     * Useful to keep timezone consistent
     *
     * @return string
     */
    public function getCurrentTimestamp()
    {
        $database = $this->getDatabase();
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
        return $this->data;
    }

    /**
     * Returns a database connection
     *
     * @return \Medoo\Medoo
     */
    public static function getDatabase()
    {
        return MedooConnection::getInstance(static::DATABASE_NAME_KEY);
    }

    /**
     * Returns model's Primary Key
     *
     * @param boolean $wrap If result should be wrapped in an array
     *                      Always true for composite primary key
     *
     * @return mixed   Usually it will be an integer
     * @return mixed[] When there is a composite primary key
     */
    public function getPk($wrap = false)
    {
        $pk = static::PRIMARY_KEY;
        if (is_array($pk)) {
            return Utils::arrayWhitelist($this->data, $pk);
        }
        $result = $this->data[$pk];
        return ($wrap ? [$pk => $result] : $result);
    }

    /**
     * Cleans model data
     *
     * @param boolean $validity Value to set into $this->valid
     */
    protected function reset($validity = null)
    {
        $this->data = null;
        $this->valid = $validity;
    }

    /*
     * CRUD methods
     * =========================================================================
     */

    /**
     * Creates a new row in the Table
     *
     * NOTE:
     * - You should validate the data before calling this method
     * - Unused columns are ignored
     *
     * @param mixed[] $data Any required data for the new row. Keys should match
     *                      values in COLUMNS
     *
     * @return boolean For success or failure
     */
    public function create($data)
    {
        if (static::READ_ONLY) {
            return false;
        }

        $this->reset(false);
        $data = static::dataCleanup($data);

        $database = $this->getDatabase();
        $stmt = $database->insert(static::TABLE, $data);
        if ($stmt->errorCode() == '00000') {
            $column = static::AUTO_INCREMENT;
            if ($column !== null) {
                $data[$column] = $database->id();
            }
            $this->data = $data;
            $this->valid = true;
        }

        return $this->valid;
    }

    /**
     * Reads a row from Table into the model
     *
     * @param mixed $where Value for Primary Key or \Medoo\Medoo where clause
     *
     * @return boolean For success or failure
     *
     * @throws \InvalidArgumentException If $where does not specify columns and
     *                                   does not match PRIMARY_KEY length
     */
    public function read($where)
    {
        /*
         * Preprocess $where
         *
         * It allows the use of a simple value (e.g. string or integer) or a
         * simple array without specifing the PRIMARY_KEY column(s)
         */
        if (!is_array($where)) {
            $where = [$where];
        }
        if (!Utils::arrayIsAssoc($where)) {
            $pk = static::PRIMARY_KEY;
            if (!is_array($pk)) {
                $pk = [$pk];
            }
            $where = @array_combine($pk, $where);
            if ($where === false) {
                throw new \InvalidArgumentException(
                    'Could not solve Primary Key'
                );
            }
        }

        $this->reset(false);

        $database = $this->getDatabase();
        $data = $database->get(static::TABLE, static::COLUMNS, $where);
        if ($data) {
            $this->data = $data;
            $this->valid = true;
        }

        return $this->valid;
    }

    /**
     * Updates a row in the Table with model's data
     *
     * @param string|string[] $columns Specify which columns to update
     *
     * @return boolean For success or failure
     */
    public function update($columns = [])
    {
        if (static::READ_ONLY) {
            return false;
        }

        if (!is_array($columns)) {
            $columns = [$columns];
        }
        $data = (empty($columns))
            ? static::dataCleanup($this->data)
            : Utils::arrayWhitelist($this->data, $columns);

        $database = $this->getDatabase();
        $stmt = $database->update(static::TABLE, $data, $this->getPk(true));
        return ($stmt->errorCode() == '00000');
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

        $database = $this->getDatabase();
        $column = static::SOFT_DELETE;
        if ($column) {
            switch (static::SOFT_DELETE_MODE) {
                case 'deleted':
                    $this->data[$column] = 1;
                    break;

                case 'active':
                    $this->data[$column] = 0;
                    break;

                case 'stamp':
                    $this->data[$column] = $this->getCurrentTimestamp();
                    break;

                default:
                    throw new \UnexpectedValueException('Unknown mode');
                    break;
            }
            return $this->update($column);
        } else {
            $stmt = $database->delete(static::TABLE, $this->getPk(true));
            $this->reset();
            return ($stmt->rowCount() > 0);
        }
    }
}
