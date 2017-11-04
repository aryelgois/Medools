<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * A County is contained in a State, which is contained in a Country
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
class County extends Medools\Model
{
    const DATABASE_NAME_KEY = 'address';

    const TABLE = 'counties';

    const COLUMNS = ['id', 'state', 'name'];

    const FOREIGN_KEYS = [
        'state' => [
            __NAMESPACE__ . '\State',
            'id'
        ],
    ];

    const READ_ONLY = true;
}
