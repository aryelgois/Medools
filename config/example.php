<?php
/**
 * Example of Configuration for MedooConnection
 */

return [
    'servers' => [
        'default' => [
            // required
            'server' => 'localhost',
            'username' => 'root',
            'password' => 'password',
            'database_type' => 'mysql',

            // [optional]
            'charset' => 'utf8',
        ],
    ],
    'databases' => [
        'default' => [
            'database_name' => 'my_database',
        ],
    ],
];
