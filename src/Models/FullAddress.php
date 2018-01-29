<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Medools;

/**
 * A Full Address model to reference a specific place in the world
 *
 * This class expands the Address database to contain more specific information
 * about the address, which should be provided by your clients.
 *
 * @see https://github.com/aryelgois/databases
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class FullAddress extends Medools\Model
{
    const TABLE = 'full_addresses';

    protected static $columns = [
        [
            'name' => 'id',
            'primary' => true,
            'auto_increment' => true,
        ],
        [
            'name' => 'county',
            'foreign' => [
                'aryelgois\Databases\Models\County',
                'id'
            ],
        ],
        [
            'name' => 'neighborhood',
        ],
        [
            'name' => 'place',
        ],
        [
            'name' => 'number',
        ],
        [
            'name' => 'zipcode',
        ],
        [
            'name' => 'detail',
            'optional' => true,
        ],
        [
            'name' => 'update',
            'optional' => true,
        ],
    ];
}
