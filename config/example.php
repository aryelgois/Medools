<?php
/**
 * Example of Configurations for aryelgois\Medools
 *
 * The option 'database_name' for Medoo\Medoo is one value in 'databases',
 * which is selected by the MedooConnection, and the key is defined in the Model
 */

return [
    'databases' => [
    //  'database_name_used_by_the_models' => 'database_name_in_the_server',
        'default'       => 'my_database',
    ],
    'options' => [
        // required
        'database_type' => 'mysql',
        'server'        => 'localhost',
        'username'      => 'root',
        'password'      => 'password',

        // [optional]
        'charset' => 'utf8',
    ]
];
