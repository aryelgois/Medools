<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * A Full Address model to reference a specific place in the world
 *
 * This class allows you to use more specific information about the Address,
 * which should be provided by your clients.
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class FullAddress extends Medools\Model
{
    const TABLE = 'full_addresses';

    const COLUMNS = [
        'id',
        'county',
        'neighborhood',
        'place',
        'number',
        'zipcode',
        'detail',
        'update'
    ];

    const OPTIONAL_COLUMNS = [
        'detail',
        'update',
    ];

    const FOREIGN_KEYS = [
        'county' => [
            /** @deprecated Replace by 'aryelgois\Databases\Models\County' */
            __NAMESPACE__ . '\County',
            'id'
        ],
    ];
}
