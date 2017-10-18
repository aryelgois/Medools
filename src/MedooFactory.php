<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use Medoo\Medoo;

/**
 * A Multiton creator of Medoo\Medoo objects
 *
 * It avoids recreate a Medoo for the same database, while implements an
 * abstraction for the Medoo configuration
 *
 * NOTES:
 * - The reason for being a static class is to be accessible from anywhere
 * - At the start of your script, you need to call loadConfig() with the path to
 *   a file in your project like in the config/example.php
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class MedooFactory
{
    /**
     * Store the configurations for a new instance
     *
     * @var array[]
     */
    private static $config;

    /**
     * Store instances of Medoo\Medoo
     *
     * @var Medoo\Medoo[]
     */
    private static $instances = [];

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
     * Loads the Medools config file in a spefic file
     *
     * You must call this method before using getInstance().
     *
     * @see config/example.php
     *
     * @param string $config_path Config file path
     */
    public static function loadConfig($config_path)
    {
        self::$config = require_once $config_path;
    }

    /**
     * Returns an instance of Medoo\Medoo connected to a specific database
     *
     * You can use the anonymous mode to get a fresh instance for advanced Medoo
     * commands, already connected to one of your Databases
     *
     * @param string  $database  Key for a database_name in the config file
     * @param boolean $anonymous If an anonymous instance should be created
     *
     * @return Medoo\Medoo
     *
     * @throws \BadMethodCallException If called before loadConfig()
     * @throws \RuntimeException       If database is not in config file
     */
    public static function getInstance($database, $anonymous = false)
    {
        if (self::$config === null) {
            throw new \BadMethodCallException('Medools config was not loaded');
        }
        if (!array_key_exists($database, self::$config['databases'])) {
            throw new \RuntimeException('Unknown database');
        }

        if ($anonymous || !array_key_exists($database, self::$instances)) {
            $options = self::$config['options'];
            $options['database_name'] = self::$config['databases'][$database];
            $instance = new Medoo($options);
            if ($anonymous) {
                return $instance;
            } else {
                self::$instances[$database] = $instance;
            }
        }

        return self::$instances[$database];
    }
}
