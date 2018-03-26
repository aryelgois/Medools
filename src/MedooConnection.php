<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools;

use Medoo\Medoo;

/**
 * A Factory of \Medoo\Medoo objects
 *
 * It avoids re-instancing a Medoo object for the same database, while
 * implements an abstraction for the Medoo configuration
 *
 * NOTE:
 * - The reason for storing in a static property is to be accessible in the
 *   whole script
 * - At the start of your script, you need to call loadConfig() with the path to
 *   a file in your project like in the config/example.php
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class MedooConnection
{
    /**
     * MedooConnection's databases cache
     *
     * @var array[]
     */
    private static $cache;

    /**
     * Store the configurations for a new instance
     *
     * @var array[]
     */
    private static $config;

    /**
     * Store instances of \Medoo\Medoo
     *
     * @var \Medoo\Medoo[]
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
     * Returns data for Medoo connect to a Database
     *
     * @param string $database Key for a database in the config
     *
     * @return array
     *
     * @throws \BadMethodCallException If called before loadConfig()
     * @throws \LogicException         If database is not in config
     */
    public static function getDatabaseConfig($database)
    {
        $cached = self::$cache[$database] ?? null;
        if ($cached !== null) {
            return $cached;
        }

        if (self::$config === null) {
            throw new \BadMethodCallException('Config was not loaded');
        }
        if (!array_key_exists($database, self::$config['databases'])) {
            throw new \LogicException("Unknown database '$database'");
        }

        $data = self::$config['databases'][$database];
        $server = $data['server'] ?? 'default';
        $servers = self::$config['servers'] ?? [];

        if (array_key_exists($server, $servers)) {
            unset($data['server']);
            $data = array_merge($servers[$server], $data);
        }

        self::$cache[$database] = $data;
        return $data;
    }

    /**
     * Returns a Medoo instance connected to a specific database
     *
     * You can use the anonymous mode to get a fresh instance for advanced Medoo
     * commands, already connected to one of your Databases
     *
     * @param string  $database  Key for a database in the config
     * @param boolean $anonymous If an anonymous instance should be created
     *
     * @return Medoo
     */
    public static function getInstance($database, $anonymous = false)
    {
        if ($anonymous || !array_key_exists($database, self::$instances)) {
            $instance = new Medoo(self::getDatabaseConfig($database));
            if ($anonymous) {
                return $instance;
            }
            self::$instances[$database] = $instance;
        }

        return self::$instances[$database];
    }

    /**
     * Loads the Medools config file from a php file
     *
     * NOTE:
     * - You must call this method before using getDatabaseConfig() and
     *   getInstance()
     *
     * @see config/example.php
     *
     * @param string $config_path Config file path
     */
    public static function loadConfig($config_path)
    {
        self::$config = require $config_path;
    }
}
