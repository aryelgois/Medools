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
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class FullAddress extends Medools\Model
{
    const DATABASE_NAME_KEY = 'my_app'; // example

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
     * Reads a FullAddress data from the Database
     *
     * @param mixed[] $where \Medoo\Medoo where clause for `counties`
     *
     * @return boolean For success or failure
     */
    public function read($where)
    {
        $this->reset();

        $full_address = $this->database->get(
            static::DATABASE_TABLE,
            static::ROWS_FULL_ADDRESS,
            $where
        );

        $this->valid = false;
        if ($full_address) {
            $address_class = array_slice(explode('\\', static::class), 0, -1);
            $address_class = implode('\\', $address_class) . '\Address';

            $address = new $address_class();
            $address->read(['id' => $full_address['county']]);
            unset($full_address['county']);

            if ($address->isValid()) {
                $this->data = $full_address;
                $this->data['address'] = $address;
                $this->valid = true;
            }
        }
        return $this->valid;
    }
}
