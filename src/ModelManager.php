<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use aryelgois\Utils;

/**
 * Manage your Models without instantiating duplicated objects
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class ModelManager
{
    /**
     * Nested list of Models
     *
     * This list creates a path to the Model instance. This path is defined by
     * the Model class name, and any amount of columns for it's PRIMARY_KEY.
     *
     * EXAMPLE:
     *     [
     *         'Fully\Qualified\ClassName' => [
     *             value for PRIMARY_KEY[0] => [
     *                 value for PRIMARY_KEY[1] => Model,
     *             ],
     *         ],
     *     ];
     *
     * @var array[] The deepest element is a Model
     */
    protected static $models = [];

    // Prevents creating multiple instances due to 'private' constructor
    private function __construct()
    {}

    // Prevents the instance from being cloned
    private function __clone()
    {}

    // Prevents from being unserialized
    private function __wakeup()
    {}

    /**
     * Creates a new Model Instancer
     *
     * @param string  $model_class A Fully Qualified Model Class
     * @param mixed[] $where       Value for Model Primary Key
     *                             or \Medoo\Medoo where clause
     *
     * @return Model Loaded from Database
     * @return null  If no entry is found in the Database
     *
     * @throws \InvalidArgumentException @see Model::processWhere()
     */
    public static function getInstance($model_class, $where)
    {
        $database = $model_class::getDatabase();
        $primary_key = $database->get(
            $model_class::TABLE,
            $model_class::PRIMARY_KEY,
            $model_class::processWhere($where)
        );
        if ($primary_key) {
            $path = array_merge([$model_class], $primary_key);
            $get_model = Utils\Utils::arrayPathGet(self::$models, $path);
            if ($get_model === null) {
                return new $model_class($primary_key);
            }
            return $get_model;
        }
    }

    /**
     * Gets the path to a Model
     *
     * @param Model $model A Model get the path
     */
    protected static function getPath(Model $model)
    {
        return array_merge([get_class($model)], $model->getPrimaryKey());
    }

    /**
     * Imports a Model into the Manager
     *
     * @param Model $model A Model to import
     */
    public static function import(Model $model)
    {
        Utils\Utils::arrayPathSet(self::$models, self::getPath($model), $model);
    }

    /**
     * Removes a Model from the Manager
     *
     * @param Model|string[] $var Model instance or Path to a Model to remove
     */
    public static function remove($var)
    {
        $path = ($var instanceof Model)
            ? self::getPath($var)
            : $var;

        Utils\Utils::arrayPathUnset(self::$models, $path);
    }
}
