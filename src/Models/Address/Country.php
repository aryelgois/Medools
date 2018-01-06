<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * A Country contains States which contain Counties
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
class Country extends Medools\Model
{
    const DATABASE_NAME_KEY = 'address';

    const TABLE = 'countries';

    const COLUMNS = [
        'id',
        'code_a2',
        'code_a3',
        'code_number',
        'name_en',
        'name_local'
    ];

    const READ_ONLY = true;
}
