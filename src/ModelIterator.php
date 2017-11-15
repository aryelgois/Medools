<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
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
     * List PRIMARY KEYS for $model
     *
     * @var mixed[]
     */
    protected $list;

    /**
     * A model to load data from Database, with one item in $list at time
     *
     * @var Model
     */
    protected $model;

    /**
     * The current PRIMARY KEY to be used from $list
     *
     * @var integer
     */
    protected $pointer;

    /**
     * Creates a new Model Iterator
     *
     * - You provide a Model instance (preferably empty) and a Medoo $where
     * - It selects PRIMARY KEYS for that model matching $where
     * - Now you can iterate over Database rows for that model, one at time
     *
     * EXAMPLE:
     *     foreach (new ModelIterator(new Model, ['id[>=]' => 1]) as $model) {
     *         // code...
     *     }
     *
     * @see https://medoo.in/api/where
     *
     * @param Model   $model A model to iterate
     * @param mixed[] $where \Medoo\Medoo where clause
     */
    public function __construct(Model $model, $where = [])
    {
        $this->model = $model;
        $this->list = $model->dump($where, $model::PRIMARY_KEY);
    }

    /**
     * Loads the model with the current pointened PRIMARY KEY and returns it
     *
     * @return Model
     */
    public function current()
    {
        $this->model->load($this->list[$this->pointer]);
        return $this->model;
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
