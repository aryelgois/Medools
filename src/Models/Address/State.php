<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * A State contains counties
 *
 * It is built on top of aryelgois\databases\Address, which means it expects
 * you have a database following that scheme in your server.
 *
 * @see https://www.github.com/aryelgois/databases
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class State extends Medools\Model
{
    const DATABASE_NAME_KEY = 'address';

    const TABLE = 'states';

    const COLUMNS = ['id', 'country', 'code', 'name'];

    const FOREIGN_KEYS = [
        'country' => [
            __NAMESPACE__ . '\Country',
            'id'
        ],
    ];

    const READ_ONLY = true;
}
