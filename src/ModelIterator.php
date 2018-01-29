<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use aryelgois\Utils;

/**
 * Easily iterate over different rows of a model
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class ModelIterator implements \Iterator
{
    /**
     * List PRIMARY KEYS for $model_class
     *
     * @var mixed[]
     */
    protected $list;

    /**
     * A model to load data from Database, with one item in $list at time
     *
     * @var string
     */
    protected $model_class;

    /**
     * The current PRIMARY KEY to be used from $list
     *
     * @var integer
     */
    protected $pointer;

    /**
     * Creates a new Model Iterator
     *
     * - You provide a Fully Qualified Model Class and a Medoo $where
     * - It loads instances of that model class matching $where
     * - Now you can iterate over Database rows for that model, one at time
     *
     * EXAMPLE:
     *     $class = 'aryelgois\Medools\Models\Person';
     *     foreach (new ModelIterator($class, ['id[>=]' => 1]) as $model) {
     *         // code...
     *     }
     *
     * @see https://medoo.in/api/where
     *
     * @param string  $model A Fully Qualified Model Class to iterate
     * @param mixed[] $where \Medoo\Medoo where clause
     */
    public function __construct($model_class, $where = [])
    {
        $this->model_class = $model_class;
        $this->list = $model_class::dump(
            $where,
            $model_class::getCache()['primaries']
        );
    }

    /**
     * Loads the model with the current pointened PRIMARY KEY and returns it
     *
     * @return Model
     */
    public function current()
    {
        return ModelManager::getInstance(
            $this->model_class,
            $this->list[$this->pointer]
        );
    }

    /**
     * Returns the index for the current PRIMARY KEY in the $list
     *
     * @return integer
     */
    public function key()
    {
        return $this->pointer;
    }

    /**
     * Forwards the pointer by one
     */
    public function next()
    {
        $this->pointer++;
    }

    /**
     * Resets the pointer to the beginning of the $list
     */
    public function rewind()
    {
        $this->pointer = 0;
    }

    /**
     * Checks if the current pointer is in the $list
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->list[$this->pointer]);
    }
}
