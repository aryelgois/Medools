<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Medools;

/**
 * A Full Address object to reference a specific place in the world
 *
 * This class allows you to use more specific information about the address,
 * which should be provided by your clients.
 *
 * NOTES:
 * - It requires a contrete Address class in the same namespace as the child
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/medoo-wrapper
 */
abstract class FullAddress extends Medools\DatabaseObject
{
    const DATABASE_TABLE = 'full_addresses';

    /**
     * Rows fetched from `full_addresses`
     *
     * @const string[]
     */
    const ROWS_FULL_ADDRESS = [
        'id',
        'county',
        'neighborhood',
        'place',
        'number',
        'zipcode',
        'detail',
        'update'
    ];

    /**
     * Creates a new FullAddress object
     *
     * @param mixed[] $where Medoo where clause
     */
    public function __construct($where)
    {
        parent::__construct();

        $full_address = $this->database->get(
            static::DATABASE_TABLE,
            static::ROWS_FULL_ADDRESS,
            $where
        );

        $this->valid = false;
        if ($full_address) {
            $address_class = array_slice(explode('\\', static::class), 0, -1);
            $address_class = implode('\\', $address_class) . '\Address';

            $address = new $address_class(['id' => $full_address['county']]);
            unset($full_address['county']);

            if ($address->isValid()) {
                $this->data = $full_address;
                $this->data['address'] = $address;
                $this->valid = true;
            }
        }
    }
}
